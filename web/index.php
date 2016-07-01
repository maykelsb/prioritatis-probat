<?php
/**
 * This file is part of Prioritatis Probat project.
 *
 * This is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3, or (at your option)
 * any later version.
 *
 * @link https://github.com/maykelsb/prioritatis-probat
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */

/**
 * Using composer psr-0 autoload as the default autoload.
 */
require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

// -- Providers
$app->register(new \Silex\Provider\SecurityServiceProvider(), [
    'security.firewalls' => [
        'auth' => ['pattern' => '^/auth/'],
        'secured' => [
            'pattern' => '^.*$',
            'form' => [
                'login_path' => '/auth/login',
                'check_path' => '/check'
            ],
            'logout' => [
                'logout_path' => '/auth/logout',
                'invalidate_session' => true
            ],
            'users' => [
                'admin@gmail.com' => ['ROLE_ADMIN', '$2y$10$3i9/lVd8UOFIJ6PAMFt8gu3/r5g0qeCJvoSlLCsvMTythye19F77a']
            ]
//            'users' => function($app){
//                return new \Pprobat\Service\UserProvider($app['db']);
//            }
        ]
    ]
]);
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\HttpFragmentServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../src/Pprobat/View',
    'twig.templates' => ['bootstrap_3_layout.html.twig']
])->extend('twig', function($twig){
    $twig->addExtension(new Pprobat\Twig\Extension\Bootstrap());
    $twig->addExtension(new Pprobat\Twig\Extension\Pprobat());
    return $twig;
});
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'drive' => 'pdo_mysql',
        'host' => '127.0.0.1',
        'dbname' => 'pprobat',
        'user' => 'pprobat',
        'password' => 'pprobat'
    ),
));
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
    'locale' => 'pt_BR'
));
$app->register(new Silex\Provider\FormServiceProvider());
$app['form.types'] = $app->extend('form.types', function($types) use ($app) {
    $types[] = new Pprobat\Form\Type\MemberType();
    $types[] = new Pprobat\Form\Type\GameType($app['db']);
    $types[] = new Pprobat\Form\Type\SessionType($app['db']);

    return $types;
});

// -- Converters
$app['converter.member'] = function($app) {
    return new Pprobat\Service\Converter\MemberConverter($app['db']);
};
$app['converter.meetup'] = function($app){
    return new Pprobat\Service\Converter\MeetupConverter($app['db']);
};
$app['converter.game'] = function($app){
    return new Pprobat\Service\Converter\GameConverter($app['db']);
};
$app['converter.session'] = function($app){
    return new Pprobat\Service\Converter\SessionConverter($app['db']);
};

// -- Controllers
$app->mount('/', new Pprobat\Controller\HomeControllerProvider())
    ->mount('/meetups', new Pprobat\Controller\MeetupControllerProvider())
    ->mount('/members', new Pprobat\Controller\MemberControllerProvider())
    ->mount('/games', new Pprobat\Controller\GameControllerProvider())
    ->mount('/about', new Pprobat\Controller\AboutControllerProvider())
    ->mount('/sessions', new Pprobat\Controller\SessionControllerProvider())
    ->mount('/auth', new Pprobat\Controller\AuthControllerProvider())
    ->run();
