<?php
/**
 * This file is part of Prioritatis Probat project.
 *
 * This is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3, or (at your option)
 * any later version.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 * @link https://github.com/maykelsb/prioritatis-probat
 */
namespace Pprobat\Controller;

use Symfony\Component\HttpFoundation\Request;

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
                'sessions' => []
            ]);

        })->bind('meetup_view')
            ->convert('meetup', 'converter.meetup:convert');
    }

//    protected function addMeetupMembersAction()
//    {
//        $this->cc->get('/{meetup}/members/add', function($meetup){
//            $sql = <<<DML
//SELECT m.name,
//       CASE WHEN mm.id IS NULL THEN 'N'
//            ELSE 'A' END AS status
//  FROM member m
//    LEFT JOIN meetup_member mm ON (m.id = mm.member AND mm.meetup = ?)
//DML;
//            $members = $this->app['db']->fetchAll($sql, [$meetup]);
//            return $this->app['twig']->render('meetup/members.html.twig', [
//                'members' => $members
//            ]);
//
//        })->bind('meetup_members_add');
//    }

//    protected function listMeetupMemberAction()
//    {
//        $this->cc->get('/{meetup}/members', function($meetup){
//
//
//
//
//            return $this->app['twig']->render('meetup/members.html.twig', [
//                'members' => []
//            ]);
//
//        })->bind('meetup_members_list');
//    }


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
