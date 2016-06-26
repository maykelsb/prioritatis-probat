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

/**
 * Controller for home requests.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
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

            $sql = <<<DML
SELECT d.id,
       d.name,
       d.own,
       d.others
  FROM (SELECT m.id,
               m.name,
               (SELECT COUNT(1)
                  FROM member_game mg
                    INNER JOIN session s ON (mg.game = s.game)
                  WHERE mg.member = m.id) AS own,
	           (SELECT COUNT(1)
                  FROM session_member sm
                  WHERE sm.member = m.id) AS others
          FROM member m) d
  ORDER BY d.others / d.own ASC,
           d.others DESC
DML;
            $data['users'] = $this->app['db']->fetchAll($sql);
            return $this->app['twig']->render('home/home.html.twig', $data);

        })->bind('home');
    }

    protected function filterData(array &$data){
    }
    protected function prePersist(array &$data){
    }
}
