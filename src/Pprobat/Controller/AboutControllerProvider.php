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
 */
namespace Pprobat\Controller;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;

/**
 * Controller for about requests.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
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