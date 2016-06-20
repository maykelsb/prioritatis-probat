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
namespace Pprobat\Twig\Extension;

class Pprobat extends AbstractTwigExtension
{
    protected function meetupTypeFilter()
    {
        return new \Twig_SimpleFilter('meetuptype', function($type){
            switch ($type) {
                case 'P':
                    return '<span class="label label-success">principal</span>';
                case 'S':
                    return '<span class="label label-warning">especial</span>';
                case 'O':
                    return '<span class="label label-default">outro</span>';
                default:
                    return '<span class="label label-danger">???</span>';
            }
        }, ['is_safe' => ['html']]);
    }

    protected function userAvatarFunction()
    {
        return new \Twig_SimpleFunction('useravatar', function($name, $status){
            $name = explode(' ', $name);
            $name = ucfirst(strtolower(current($name))) . ' ' . strtoupper(substr(end($name), 0, 1)) . '.';
            return <<<HTML
<div class="col-md-2">
    <div class="user-avatar">
        <div class="user-name">{$name}</div>
    </div>
</div>
HTML;
        }, ['is_safe' => ['html']]);
    }

    public function getName() {
        return 'pprobat';
    }
}

