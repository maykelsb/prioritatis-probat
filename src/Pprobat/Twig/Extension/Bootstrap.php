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
            $this->alertFilter(),
            $this->labelFilter()
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
            $validClasses = ['danger', 'info', 'warning', 'default', 'success', 'primary'];

            if (!in_array($options[0], $validClasses)) {
                $msg = 'Only "danger", "info", "warning", "default", "primary" and "success" are valid params for bt_alert.';
                $msg .= ' "' . $options[0] . '" received.';
                throw new \Twig_Error_Syntax($msg);
            }

            return <<<HTML
<div class="alert alert-{$options[0]} text-center" role="alert">{$string}</div>
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

    public function getName() {
        return 'bootstrap';
    }
}