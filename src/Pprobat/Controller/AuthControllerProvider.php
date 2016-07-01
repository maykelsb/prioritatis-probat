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
namespace Pprobat\Controller;

use Symfony\Component\HttpFoundation\Request;


class AuthControllerProvider extends AbstractControllerProvider
{
    protected function loginAction()
    {
        $this->cc->get('/login', function(Request $request){
            return $this->app['twig']->render('auth/login.html.twig', [
                'error' => $this->app['security.last_error']($request),
                'last_username' => $this->app['session']->get('_security.last_username')
            ]);
        })->bind('auth_login');

        return $this;
    }

    protected function checkAction()
    {
        $this->cc->get('/check', function(Request $request){

            die(var_dump($request));

//            return $this->app['twig']->render('auth/login.html.twig');
        })->bind('auth_check');

        return $this;
    }




    protected function prePersist(array &$data) {
        ;
    }

    protected function filterData(array &$data) {
        ;
    }
}
