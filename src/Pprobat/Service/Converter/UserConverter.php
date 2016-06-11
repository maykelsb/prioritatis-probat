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
        return $this->db->fetchAssoc($query, [$id]);
    }
}
