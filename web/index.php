<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

// -- Providers
$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../src/Pprobat/View',
    'twig.templates' => ['bootstrap_3_layout.html.twig']
])->extend('twig', function($twig){
    $twig->addExtension(new Pprobat\Twig\Extension\Bootstrap());

    // -- @Todo: Migrar to a class
    $twig->addFilter(new Twig_SimpleFilter('meetuptype', function($type){
        switch ($type) {
            case 'P':
                return '<span class="label label-success">principal</span>';
            case 'S':
                return '<span class="label label-warning">especial</span>';
            case 'O':
                return '<span class="label label-default">outro</span>';
            default:
                return '<span class="label label-danger">???</span>';
        }
    }, ['is_safe' => ['html']]));
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
$app['form.types'] = $app->extend('form.types', function ($types) use ($app) {
    $types[] = new Pprobat\Form\Type\MemberType();
    $types[] = new Pprobat\Form\Type\GameType($app['db']);

    return $types;
});

// -- Converters
$app['converter.member'] = function () use ($app) {
    return new Pprobat\Service\Converter\MemberConverter($app['db']);
};
$app['converter.meetup'] = function() use ($app){
    return new Pprobat\Service\Converter\MeetupConverter($app['db']);
};
$app['converter.game'] = function() use ($app){
    return new Pprobat\Service\Converter\GameConverter($app['db']);
};

// -- Controllers
$app->mount('/', new Pprobat\Controller\HomeControllerProvider())
    ->mount('/meetups', new Pprobat\Controller\MeetupControllerProvider())
    ->mount('/members', new Pprobat\Controller\MemberControllerProvider())
    ->mount('/games', new Pprobat\Controller\GameControllerProvider())
    ->mount('/about', new Pprobat\Controller\AboutControllerProvider())
    ->run();
