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

/**
 * Extends twig by adding a filters and functions as helpers for twitter bootstrap.
 */
class Bootstrap extends AbstractTwigExtension
{
    protected function alertFilter()
    {
        return new \Twig_SimpleFilter('bt_alert', function($string, array $options = []){
            $validClasses = ['danger', 'info', 'warning', 'default', 'success', 'primary'];

            if (!in_array($options[0], $validClasses)) {
                $msg = 'Only "danger", "info", "warning", "default", "primary" and "success" are valid params for bt_alert.';
                $msg .= ' "' . $options[0] . '" received.';
                throw new \Twig_Error_Syntax($msg);
            }

            return <<<HTML
<div class="col-md-8 col-md-offset-2 alert alert-{$options[0]} text-center" role="alert">{$string}</div>
HTML;
        }, ['is_variadic' => true, 'is_safe' => ['html']]);
    }

    protected function labelFilter()
    {
        return new \Twig_SimpleFilter('bt_label', function($string, array $options = []){

            $validClasses = ['danger', 'info', 'warning', 'default', 'success', 'primary'];
            if (!in_array($options[0], $validClasses)) {
                $msg = 'Only "danger", "info", "warning", "default", "primary" and "success" are valid params for bt_label.';
                $msg .= ' "' . $options[0] . '" received.';
                throw new \Twig_Error_Syntax($msg);
            }

            return <<<HTML
<span class="label label-{$options[0]}">{$string}</span>
HTML;
        }, ['is_variadic' => true, 'is_safe' => ['html']]);
    }

    protected function glyphiconFunction()
    {
        return new \Twig_SimpleFunction('bt_glyph', function($glyph){
            return <<<HTML
<span class="glyphicon glyphicon-{$glyph}"></span>
HTML;
        }, ['is_safe' => ['html']]);
    }

    protected function buttonFunction()
    {
        return new \Twig_SimpleFunction('bt_button', function($text, $glyph, array $options = []){
            $class = isset($options['class'])?$options['class']:'';
            $type =  isset($options['type'])?$options['type']:'default';
            return <<<HTML
<button type="button" class="btn btn-{$type} {$class}">
    <span class="glyphicon glyphicon-{$glyph}"></span>&nbsp;{$text}
</button>
HTML;

        }, ['is_safe' => ['html']]);
    }

    public function getName() {
        return 'bootstrap';
    }
}