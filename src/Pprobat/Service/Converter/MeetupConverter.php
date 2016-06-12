<?php
/**
 * A meetup converter.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */

namespace Pprobat\Service\Converter;

/**
 * Implements a converter to transform an meetupid in a row with all meetup data.
 */
class MeetupConverter
{
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function convert($id)
    {
        if (is_null($id)) {
            return [
                'meetuptype' => 'A',
                'happening' => new \DateTime('now')
            ];
        }

        $query = <<<DML
SELECT *
  FROM meetup
  WHERE id = ?
DML;

        $data = $this->db->fetchAssoc($query, [$id]);

        $data['happening'] = new \DateTime($data['happening']);

        return $data;
    }
}
