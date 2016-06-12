<?php
/**
 * Basic controller to manage games.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
namespace Pprobat\Controller;

use Symfony\Component\HttpFoundation\Request;


class GameControllerProvider extends AbstractControllerProvider
{
    protected function enableRoutes() {
        $this->listGames()
            ->editGame()
            ->viewGame();
    }

    protected function listGames()
    {
        $this->cc->get('/', function(){
            $sql = <<<DML
SELECT g.id,
       g.name,
       u.name AS designer
  FROM game g
    INNER JOIN user_game ug ON(g.id = ug.game)
    INNER JOIN user u ON(u.id = ug.user)
DML;
            $games = $this->app['db']->fetchAll($sql);
            return $this->app['twig']->render('game/list.html.twig', [
                'games' => $games
            ]);

        })->bind('games_list');

        return $this;
    }

    protected function editGame()
    {
        $this->cc->match('/edit/{game}', function(Request $request, $game = null){
            $game['designers'] = $game['designers']['id'];

            return $this->handleForm($request, $game);

        })->bind('game_edit')
            ->value('game', null)
            ->convert('game', 'converter.game:convert');

        return $this;
    }

    protected function viewGame()
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

            $this->app['db']->delete('user_game', ['game' => $gameId]);
        }
        $this->app['db']->insert('user_game', [
            'game' => $gameId,
            'user' => $designer
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