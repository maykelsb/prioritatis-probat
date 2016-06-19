<?php
/**
 * This file is part of Prioritatis Probat project.
 *
 * This is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3, or (at your option)
 * any later version.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 * @link https://github.com/maykelsb/prioritatis-probat
 */
namespace Pprobat\Controller;



class SessionControllerProvider extends AbstractControllerProvider
{
    protected function listSessionsAction()
    {
        $this->cc->get('/', function(){

        })->bind('sessions_list');

        return $this;
    }

    protected function filterData(array &$data) {
        ;
    }

    protected function prePersist(array &$data) {
        ;
    }
}
