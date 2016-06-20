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
 * Implements methods to load filters and functions from a twig extension.
 */
abstract class AbstractTwigExtension extends \Twig_Extension
{
    /**
     * Load all filters of the extension.
     *
     * To automatically load a filter its declaration method's name has to
     * end with 'Filter'.
     *
     * @return mixed[] Twig_SimpleFilter
     */
    public function getFilters()
    {
        $filters = [];

        foreach (get_class_methods($this) as $method) {
            if ('Filter' === substr($method, -6)) {
                $filters[] = $this->$method();
            }
        }

        return $filters;
    }

    /**
     * Load all functions of the extension.
     *
     * To automatically load a function its declaration method's name has to
     * end with 'Function'.
     *
     * @return mixed[] Twig_SimpleFunction
     */
    public function getFunctions() {
        $functions = [];

        foreach (get_class_methods($this) as $method) {
            if ('Function' === substr($method, -8)) {
                $functions[] = $this->$method();
            }
        }

        return $functions;
    }
}
