<?php
/**
 * A member converter.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */

namespace Pprobat\Service\Converter;

/**
 * Implements a converter to transform an memberid in a row with all member data.
 */
class MemberConverter
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
SELECT m.*,
       g.name AS gamename,
       g.id AS gameid
  FROM member m
    LEFT JOIN member_game mg ON(m.id = mg.member)
    LEFT JOIN game g ON(mg.game = g.id)
  WHERE m.id = ?
DML;

        $data = $this->db->fetchAll($query, [$id]);

        $member = $data[0];
        unset($member['gamename'], $member['gameid']);
        $member['games'] = [];

        foreach ($data as $_data) {
            if (empty($_data['gameid'])) {
                continue;
            }

            $member['games'][] = [
                'id' => $_data['gameid'],
                'name' => $_data['gamename']
            ];
        }

        $member['affiliation'] = new \DateTime($member['affiliation']);
        $member['creation'] = new \DateTime($member['creation']);

        return $member;
    }
}
