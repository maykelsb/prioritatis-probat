<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Pprobat\Controller\HomeControllerProvider;
use Pprobat\Controller\MemberControllerProvider;
use Pprobat\Controller\MeetupControllerProvider;
use Pprobat\Controller\AboutControllerProvider;

use Pprobat\Twig\Extension\Bootstrap;

use Pprobat\Form\Type\MemberType;

$app = new Silex\Application();
$app['debug'] = true;

// -- Providers
$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../view',
    'twig.templates' => ['bootstrap_3_layout.html.twig']
])->extend('twig', function($twig){
    $twig->addExtension(new Bootstrap());
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
$app->register(new Silex\Provider\FormServiceProvider(), []);
$app['form.types'] = $app->extend('form.types', function ($types) use ($app) {
    $types[] = new MemberType();

    return $types;
});

$app->mount('/', new HomeControllerProvider())
    ->mount('/members', new MemberControllerProvider())
    ->mount('/meetups', new MeetupControllerProvider())
    ->mount('/about', new AboutControllerProvider())
    ->run();
