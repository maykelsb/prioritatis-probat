<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../views'
])->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'drive' => 'pdo_mysql',
        'host' => '127.0.0.1',
        'dbname' => 'pprobat',
        'user' => 'pprobat',
        'password' => 'pprobat'
    ),
));

$app->get('/', function() use ($app) {
    return $app['twig']->render('home.html.twig', []);
})->bind('index');

$app->get('/members', function() use ($app){
    return $app['twig']->render('members.html.twig', [
        'members' => $app['db']->fetchAll('SELECT name FROM user')
    ]);
})->bind('get_members');

$app->get('/meetups', function() use ($app){
    return $app['twig']->render('meetups.html.twig', []);
})->bind('get_meetups');

$app->get('/about', function() use ($app){
    return $app['twig']->render('about.html.twig', []);
})->bind('get_about');

$app->run();