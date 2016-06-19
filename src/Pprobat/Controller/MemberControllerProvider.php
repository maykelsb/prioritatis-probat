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

class MemberControllerProvider extends AbstractControllerProvider
{
    protected function listMembersAction()
    {
        $this->cc->get('/', function(){
            $members = $this->app['db']->fetchAll('SELECT id, username, name, affiliation FROM member');

            return $this->app['twig']->render('member/list.html.twig', [
                'members' => $members
            ]);
        })->bind('members_list');

        return $this;
    }

    protected function editMemberAction()
    {
        $this->cc->match('/edit/{member}', function(Request $request, $member = null){

            return $this->handleForm($request, $member);
        })->bind('member_edit')
            ->value('member', null)
            ->convert('member', 'converter.member:convert');

        return $this;
    }

    protected function viewMemberAction()
    {
        $this->cc->get('/view/{member}', function($member){
            return $this->app['twig']->render('member/view.html.twig', [
                'member' => $member
            ]);

        })->bind('member_view')
        ->convert('member', 'converter.member:convert');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function filterData(array &$data)
    {
        filter_var_array($data, [
            'name' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
            'about' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
            'username' => [FILTER_SANITIZE_MAGIC_QUOTES],
            'password' => [FILTER_SANITIZE_MAGIC_QUOTES],
        ]);
    }

    protected function prePersist(array &$data) {
        $data['affiliation'] = $data['affiliation']->format('Y-m-d');

        if (isset($data['creation'])) {
            $data['creation'] = $data['creation']->format('Y-m-d');
        }
    }
}
