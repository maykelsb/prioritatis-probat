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
namespace Pprobat\Service\Converter;

/**
 * A service which converts game id in a game data array.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
class GameConverter
{
    public function convert($id)
    {
        if (is_null($id)) {
            return null;
        }

        $query = <<<DML
SELECT g.*,
       u.id AS designerid,
       u.name AS designer
  FROM game g
    INNER JOIN member_game ug ON (g.id = ug.game)
    INNER JOIN member u ON (ug.member = u.id)
  WHERE g.id = ?
DML;

        $game = $this->db->fetchAssoc($query, [$id]);
        $game['designers'] = [
            'id' => $game['designerid'],
            'name' => $game['designer']
        ];
        unset($game['designerid'], $game['designer']);

        return $game;
    }
}
