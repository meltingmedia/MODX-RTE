<?php namespace Melting\MODX\RTE\Type;

use Melting\MODX\RTE\BaseRTE;

/**
 * CKEditor support
 */
class CKEditor extends BaseRTE
{
    protected $override = 'ckeditor-overrides.js';

    /**
     * @inherit
     */
    public function getOptions()
    {
        // Pool of system settings handled to "craft" the RTE
        $options = [
            'skin',
            'ui_color',
            'toolbar',
            'toolbar_groups',
            'format_tags',
            'extra_plugins',
            'remove_plugins',
            'styles_set',
            'startup_mode',
            'undo_size',
            'autocorrect_dash',
            'autocorrect_double_quotes',
            'autocorrect_single_quotes',
            'object_resizing',
            'native_spellchecker',
        ];

        $overrides = [];
        // Since the implementation is looking inside MODx.config JS array, let's override it
        foreach ($options as $k) {
            $v = $this->getSetting($k);
            $overrides[] = "MODx.config['ckeditor.{$k}'] = '{$v}';";
        }
        $overrides = implode("\n", $overrides);
        $this->modx->controller->addHtml(<<<HTML
<script>
{$overrides}
</script>
HTML
        );

        return [
            'editor' => 'CKEditor',
        ];
    }
}
