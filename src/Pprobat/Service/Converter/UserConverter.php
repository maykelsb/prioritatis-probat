<?php
/**
 * A user converter.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */

namespace Pprobat\Service\Converter;

/**
 * Implements a converter to transform an userid in a row with all user data.
 */
class UserConverter
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
  FROM user
  WHERE id = ?
DML;

        $data = $this->db->fetchAssoc($query, [$id]);

        $data['affiliation'] = new \DateTime($data['affiliation']);
        $data['creation'] = new \DateTime($data['creation']);

        return $data;
    }
}
