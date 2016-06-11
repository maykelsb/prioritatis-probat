<?php
namespace Pprobat\Controller;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class AboutControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app) {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function() use ($app){
            return $app['twig']->render('about/about.html.twig', []);
        })->bind('get_about');

        return $controllers;
    }
}