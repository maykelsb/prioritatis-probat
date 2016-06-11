<?php
namespace Pprobat\Controller;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;

class MeetupControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app) {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function() use ($app){
            return $app['twig']->render('meetups/list.html.twig', []);
        })->bind('get_meetups');

        return $controllers;
    }
}