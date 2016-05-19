<?php namespace Melting\MODX\RTE\Type;

use Melting\MODX\RTE\BaseRTE;

/**
 * New TinyMCE support
 */
class TinyMCERTE extends BaseRTE
{
    protected $override = 'tinymcerte-overrides.js';
    protected $prefix = 'tinymcerte';
}
