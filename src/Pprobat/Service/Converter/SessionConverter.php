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
 * A service which converts session id in a session data array.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
class SessionConverter extends AbstractConverter
{
    /**
     * {@inheritdoc}
     */
    public function convert($id) {
        if (is_null($id)) {
            return null;
        }


    }
}