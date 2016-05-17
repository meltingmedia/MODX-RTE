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
        $settings = $this->rte->getRTEOptions();

        $overrides = [];
        // Since the implementation is looking inside MODx.config JS array, let's override it
        foreach ($settings as $k) {
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
