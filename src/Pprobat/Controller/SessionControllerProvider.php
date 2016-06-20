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

use Symfony\Component\HttpFoundation\Request;

class SessionControllerProvider extends AbstractControllerProvider
{
    protected function listSessionsAction()
    {
        $this->cc->get('/{meetup}', function($meetup){
            $sql = <<<DML
SELECT s.id,
       m.title AS meetup,
       g.name AS game,
       COUNT(sm.id) AS numplayers
  FROM session s
    INNER JOIN meetup m ON (s.meetup = m.id)
    LEFT JOIN game g ON (s.game = g.id)
    LEFT JOIN session_member sm ON (s.id = sm.session)
  GROUP BY s.id,
           m.title,
           g.name
DML;
            return $this->app['twig']->render('session/list.html.twig', [
                'meetup' => $meetup,
                'sessions' => $this->app['db']->fetchAll($sql)
            ]);
        })->bind('sessions_list')
            ->value('meetup', null);

        return $this;
    }

    protected function editSessionAction()
    {
        $this->cc->match('/{meetup}/edit/{session}', function($meetup, $session, Request $request){






        })->bind('session_edit')
            ->value('session', null)
            ->convert('session', 'converter.session:convert');
    }









    protected function filterData(array &$data) {
        ;
    }

    protected function prePersist(array &$data) {
        ;
    }
}
