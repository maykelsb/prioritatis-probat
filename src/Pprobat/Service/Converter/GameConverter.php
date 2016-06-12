<?php
/**
 * A game converter.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
namespace Pprobat\Service\Converter;

/**
 * Implements a converter to transform an gameid in a row with all game data.
 */
class GameConverter
{
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

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
