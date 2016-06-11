<?php
namespace Pprobat\Controller;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class HomeControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app) {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function() use ($app){
            return $app['twig']->render('home/home.html.twig', []);
        })->bind('index');

        return $controllers;
    }
}