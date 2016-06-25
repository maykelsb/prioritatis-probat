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

/**
 * Controller for meetup requests.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
class MeetupControllerProvider extends AbstractControllerProvider
{
    protected function listMeetupsAction()
    {
        $this->cc->get('/', function(){
            $sql = <<<DML
SELECT id,
       title,
       local,
       happening,
       meetuptype,
       notes
  FROM meetup
DML;
            $meetups = $this->app['db']->fetchAll($sql);

            return $this->app['twig']->render('meetup/list.html.twig', [
                'meetups' => $meetups
            ]);
        })->bind('meetups_list');

        return $this;
    }

    protected function editMeetupsAction()
    {
        $this->cc->match('/edit/{meetup}', function(Request $request, $meetup = null){

            return $this->handleForm($request, $meetup);
        })->bind('meetup_edit')
            ->value('meetup', null)
            ->convert('meetup', 'converter.meetup:convert');

        return $this;
    }

    protected function viewMeetupAction()
    {
        $this->cc->get('/view/{meetup}', function($meetup){
            return $this->app['twig']->render('meetup/view.html.twig', [
                'meetup' => $meetup,
                'sessions' => $this->getMeetupSessions($meetup['id'])
            ]);

        })->bind('meetup_view')
            ->convert('meetup', 'converter.meetup:convert');
    }

    protected function getMeetupSessions($meetupid)
    {
        $sql = <<<DML
SELECT s.id,
       g.name AS game,
       d.name AS designer,
       p.name AS player
  FROM session s
    LEFT JOIN game g ON (s.game = g.id)
    LEFT JOIN member_game mg ON (g.id = mg.game)
    LEFT JOIN member d ON (mg.member = d.id)
    LEFT JOIN session_member sm ON (s.id = sm.session)
    LEFT JOIN member p ON (sm.member = p.id)
  WHERE s.meetup = :meetup
DML;

        $sessions = [];
        foreach ($this->app['db']->fetchAll($sql, ['meetup' => $meetupid]) as $session)
        {
            if (array_key_exists($session['id'], $sessions)) {
                $sessions[$session['id']]['players'][] = $session['player'];
                continue;
            }

            $sessions[$session['id']] = [
                'id' => $session['id'],
                'game' => $session['game'],
                'designer' => $session['designer'],
                'players' => [$session['player']]
            ];
        }

        return $sessions;
    }

    /**
     * {@inheritdoc}
     */
    protected function filterData(array &$data)
    {
        filter_var_array($data, [
            'titulo' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
            'local' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
            'notes' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
            'report' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function prePersist(array &$data) {
        $data['happening'] = $data['happening']->format('Y-m-d H:m');
    }
}
