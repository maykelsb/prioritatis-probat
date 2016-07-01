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
namespace Pprobat\Service;

use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username) {

        die(var_dump($username));

        ;
    }

    public function supportsClass($class) {

        die(var_dump($class));

        ;
    }

    public function refreshUser(\Symfony\Component\Security\Core\User\UserInterface $user) {
        ;
    }


}