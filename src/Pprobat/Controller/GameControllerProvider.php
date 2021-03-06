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
 * Controller for games requests.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
class GameControllerProvider extends AbstractControllerProvider
{
    /**
     * Registers a get route to list all games and its designers.
     *
     * @return \Pprobat\Controller\GameControllerProvider
     */
    protected function listGamesAction()
    {
        $this->cc->get('/', function(){
            $sql = <<<DML
SELECT g.id,
       g.name,
       u.name AS designer
  FROM game g
    INNER JOIN member_game ug ON(g.id = ug.game)
    INNER JOIN member u ON(u.id = ug.member)
DML;
            $games = $this->app['db']->fetchAll($sql);
            return $this->app['twig']->render('game/list.html.twig', [
                'games' => $games
            ]);

        })->bind('games_list');

        return $this;
    }

    /**
     * Registers a get/post route to edit and create new games.
     *
     * @return \Pprobat\Controller\GameControllerProvider
     */
    protected function editGameAction()
    {
        $this->cc->match('/edit/{game}', function(Request $request, $game = null){
            $game['designers'] = $game['designers']['id'];

            return $this->handleForm($request, $game);

        })->bind('game_edit')
            ->value('game', null)
            ->convert('game', 'converter.game:convert');

        return $this;
    }

    /**
     * Registers a get route to view all game details.
     *
     * @return \Pprobat\Controller\GameControllerProvider
     */
    protected function viewGameAction()
    {
        $this->cc->get('/view/{game}', function($game){
            return $this->app['twig']->render('game/view.html.twig', [
                'game' => $game
            ]);
        })->bind('game_view')
            ->convert('game', 'converter.game:convert');

        return $this;
    }

    protected function persist($newData, $initialData)
    {
        $this->filterData($newData);
        $this->prePersist($newData);

        $designer = $newData['designers'];
        unset($newData['designers']);

        if (!isset($initialData['id'])) {
            $this->app['db']->insert($this->ctrlName, $newData);
            $gameId = $this->app['db']->lastInsertId();
        } else {
            $gameId = $initialData['id'];
            $this->app['db']->update($this->ctrlName, $newData, ['id' => $gameId]);

            $this->app['db']->delete('member_game', ['game' => $gameId]);
        }
        $this->app['db']->insert('member_game', [
            'game' => $gameId,
            'member' => $designer
        ]);

        return $gameId;
    }

    protected function filterData(array &$data) {
        filter_var_array($data, [
            'name' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
            'description' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
        ]);
    }

    protected function prePersist(array &$data) {}
}