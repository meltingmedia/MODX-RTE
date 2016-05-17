<?php namespace Melting\MODX\RTE\Type;

use Melting\MODX\RTE\BaseRTE;

/**
 * New TinyMCE support
 */
class TinyMCERTE extends BaseRTE
{
    protected $override = 'tinymcerte-overrides.js';

    /**
     * @inherit
     */
    public function getOptions()
    {
        $settings = $this->rte->getRTEOptions();
        foreach ($settings as $k) {
            $this->modx->setOption("tinymcerte.{$k}", $this->getSetting($k));
        }

        return [
            'editor' => 'TinyMCE RTE',
        ];
    }
}
