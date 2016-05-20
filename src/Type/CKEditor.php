<?php namespace Melting\MODX\RTE\Type;

use Melting\MODX\RTE\BaseRTE;

/**
 * CKEditor support
 */
class CKEditor extends BaseRTE
{
    protected $override = 'ckeditor-overrides.js';
    protected $prefix = 'ckeditor';

    /**
     * @inherit
     */
    public function getOptions()
    {
        $settings = $this->rte->getRTEOptions();
        $options = [];
        $overrides = [
            "window['_ckeditor'] = {};"
        ];
        // Since the implementation is looking inside MODx.config JS array, let's override it
        foreach ($settings as $k => $value) {
            if ($this->isValidSetting($k)) {
                $key = $this->getRTEKey($k);
                $this->modx->setOption("{$this->prefix}.{$key}", $value);
                $options[$key] = $value;
                $overrides[] = "MODx.config['{$this->prefix}.{$key}'] = '{$value}';";
                $overrides[] = "window['_ckeditor']['{$key}'] = {$value};";
            }
        }
        $overrides = implode("\n", $overrides);
        $this->modx->controller->addHtml(<<<HTML
<script>
{$overrides}
</script>
HTML
        );

        return $options;
    }
}
