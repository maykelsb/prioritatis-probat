<?php
/**
 * Extends twig by adding a filters as helpers for bootstrap.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
namespace Pprobat\Twig\Extension;

class Bootstrap extends \Twig_Extension
{
    public function getFilters() {

        return [
            $this->alertFilter()
        ];
    }

    public function getFunctions() {
        return [
            $this->glyphiconFunction()
        ];
    }

    protected function alertFilter()
    {
        return new \Twig_SimpleFilter('bt_alert', function($string, array $options = []){
            if (!in_array($options[0], ['danger', 'info', 'warning', 'default', 'success'])) {
                $msg = 'Only "danger", "info", "warning", "default" and "success" are valid params for bt_alert.';
                $msg .= ' "' . $options[0] . '" received.';
                throw new \Twig_Error_Syntax($msg);
            }

            return <<<HTML
<div class="alert alert-{$options[0]} text-center" role="alert">{$string}</div>
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

    public function getName() {
        return 'bootstrap';
    }
}