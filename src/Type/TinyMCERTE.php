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
        $settings = [
            'plugins',
            'toolbar1',
            'toolbar2',
            'toolbar3',
            //'modxlinkSearch',
            //'language',
            //'directionality',
            'menubar',
            'statusbar',
            'image_advtab',
            'paste_as_text',
            'style_formats_merge',
            'object_resizing',
            'link_class_list',
            'browser_spellcheck',
            'content_css',
            'image_class_list',
            'skin',

            'style_formats',
            'external_config',
            'object_resizing',
            
            'relative_urls',
        ];
        foreach ($settings as $k) {
            $this->modx->setOption("tinymcerte.{$k}", $this->getSetting($k));
        }

        return [
            'editor' => 'TinyMCE RTE',
        ];
    }
}
