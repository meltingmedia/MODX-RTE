<?php namespace Melting\MODX\RTE\Type;

use Melting\MODX\RTE\BaseRTE;

/**
 * Redactor support
 */
class Redactor extends BaseRTE
{
    protected $override = 'redactor-overrides.js';

    /**
     * @inherit
     */
    public function getOptions()
    {
        return [

        ];
    }
}
