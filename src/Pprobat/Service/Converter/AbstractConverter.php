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

use Doctrine\DBAL\Connection;

/**
 * Abstract class for converters.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
abstract class AbstractConverter
{
    /**
     * @var Doctrine\DBAL\Connection
     */
    protected $db;

    public function __construct(Connection $db) {
        $this->db = $db;
    }

    /**
     * The converter method.
     *
     * @param int|null $id The id to be converted
     */
    abstract function convert($id);
}
