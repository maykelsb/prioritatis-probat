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
SELECT *
  FROM member
  WHERE id = ?
DML;

        $data = $this->db->fetchAssoc($query, [$id]);

        $data['affiliation'] = new \DateTime($data['affiliation']);
        $data['creation'] = new \DateTime($data['creation']);

        return $data;
    }
}
