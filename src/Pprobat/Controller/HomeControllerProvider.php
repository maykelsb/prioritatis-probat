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

use Silex\Application;

class HomeControllerProvider extends AbstractControllerProvider
{
    protected function homeAction()
    {
        $this->cc->get('/', function(){
            $sql = <<<DML
SELECT (SELECT COUNT(1)
          FROM game) AS qtdgames,
       (SELECT COUNT(1)
          FROM member) AS qtdmembers,
       (SELECT COUNT(1)
          FROM session) AS qtdsessions
DML;
            $data = $this->app['db']->fetchAssoc($sql);
            return $this->app['twig']->render('home/home.html.twig', $data);

        })->bind('home');
    }

//        $this->cc->get('/', function(){
//            $sql = <<<DML
//SELECT g.id,
//       g.name,
//       u.name AS designer
//  FROM game g
//    INNER JOIN member_game ug ON(g.id = ug.game)
//    INNER JOIN member u ON(u.id = ug.member)
//DML;
//            $games = $this->app['db']->fetchAll($sql);
//            return $this->app['twig']->render('game/list.html.twig', [
//                'games' => $games
//            ]);
//
//        })->bind('games_list');
//
//        return $this;


//    public function connect(Application $app) {
//        $controllers = $app['controllers_factory'];
//
//        $controllers->get('/', function() use ($app){
//            return $app['twig']->render('home/home.html.twig', []);
//        })->bind('index');
//
//        return $controllers;
//    }

    protected function filterData(array &$data){

    }
    protected function prePersist(array &$data){

    }
}