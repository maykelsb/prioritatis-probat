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
use Pprobat\Form\Type\SessionType;

/**
 * Controller for sessions requests.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
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
            $session['meetup'] = $meetup;

            $form = $this->app['form.factory']
                ->createBuilder(SessionType::class, $session)
                ->getForm();
            $form->handleRequest($request);

            if ($form->isSubmitted() && !$form->isValid()) {
                $this->app['session']->getFlashBag()->add(
                    'danger', 'Não foi possível processar sua requisição.'
                );
            }

            $this->ctrlName = strtolower($this->ctrlName);
            if (!$form->isValid() || !$form->isSubmitted()) {
                return $this->app['twig']->render("{$this->ctrlName}/form.html.twig", [
                    'form' => $form->createView()
                ]);
            }

            $id = $this->persist($form->getData(), $session);
            $this->app['session']->getFlashBag()->add(
                'success', 'Requisição processada com sucesso.'
            );

            return $this->app->redirect(
                $this->app['url_generator']->generate(
                    'meetup_view',
                    ['meetup' => $meetup]
                )
            );
        })->bind('session_edit')
            ->value('session', null)
            ->convert('session', 'converter.session:convert');
    }

    protected function persist($newData, $initialData)
    {
        $members = $newData['member'];
        unset($newData['member']);

        if (!isset($initialData['id'])) {

            $this->app['db']->insert('session', $newData);
            $sessionid = $this->app['db']->lastInsertId();
        } else {

            $this->app['db']->update('session', $newData, ['id' => $initialData['id']]);
            $sessionid = $initialData['id'];
            $this->app['db']->delete('session_member', ['session' => $sessionid]);
        }

        foreach ($members as $member) {
            $this->app['db']->insert('session_member', [
                'session' => $sessionid,
                'member' => $member
            ]);
        }

        return $sessionid;
    }

    protected function filterData(array &$data) {}
    protected function prePersist(array &$data) {}
}
