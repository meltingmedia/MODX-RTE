<?php namespace Melting\MODX\RTE;

use modX;

/**
 * A service to help load the appropriate configuration/assets for our RTE
 */
class Loader
{
    /**
     * A modX instance
     *
     * @var \modX
     */
    public $modx;
    /**
     * @var array
     */
    public $config = [];
    /**
     * The active editor name
     *
     * @var string|null
     */
    protected $editor;
    /**
     * RTE options defined
     *
     * @var array
     */
    protected $options = [];
    /**
     * The prefix used in system settings keys, ie. "tiny.", "redactor."
     *
     * @var string
     */
    protected $rtePrefix = '';
    /**
     * Store per field options/configuration
     *
     * @var array
     */
    protected $fields = [];

    public function __construct(modX $modx, array $options = [])
    {
        $this->modx = $modx;
        $this->config = array_merge([
            'namespace' => null,
            'empty_setting_value' => 'none',
        ], $options);
    }

    /**
     * Get the configured editor name
     *
     * @return string
     */
    protected function getEditorName()
    {
        // First check for an RTE defined on our particular "namespace"
        $editor = $this->modx->getOption(
            "{$this->config['namespace']}.which_editor",
            null,
            $this->modx->getOption("{$this->config['namespace']}.which_editor", $this->options, null)
        );
        if (!$editor || empty($editor)) {
            // No particular namespace editor found, let's fall back to the global one
            $editor = $this->modx->getOption('which_editor', null, null);
        } else {
            // We have an RTE defined, which might not be the "default" system wide one (which_editor setting)
            $this->modx->setOption('which_editor', $editor);
        }

        return $editor;
    }

    /**
     * Set RTE options
     *
     * @param array $options
     */
    public function setRTEOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getRTEOptions()
    {
        return $this->options;
    }

    /**
     * List supported RTEs classes
     *
     * @return array
     */
    protected function getSupportedRTEs()
    {
        return [
            'CKEditor',
            'Redactor',
            'TinyMCE',
            'TinyMCERTE',
        ];
    }

    /**
     * Set a particular configuration to the given field
     * 
     * @param string $fieldID
     * @param array $options
     */
    public function setFieldOptions($fieldID, array $options)
    {
        $this->fields[$fieldID] = $options;
    }

    /**
     * Instantiate the appropriate RTE class/handler
     *
     * @return void
     */
    public function load()
    {
        $this->editor = str_replace(' ', '', $this->getEditorName());
        if (!$this->editor || empty($this->editor)) {
            return;
        }
        $supported = $this->getSupportedRTEs();
        if (in_array($this->editor, $supported)) {
            $this->rtePrefix = $this->getRTEPrefix();
            $editor = '\\Melting\\MODX\\RTE\\Type\\' . $this->editor;
            /** @var BaseRTE $rte */
            $rte = new $editor($this);
            $options = $rte->getOptions();
            if (!is_array($options)) {
                $options = [];
            }

            $fields = '';
            foreach ($this->fields as $id => $o) {
                $o = json_encode($o);
                $fields .= "RTE.setFieldConfig('{$id}', {$o});";
            }

            $class = file_get_contents(dirname(__DIR__) ."/assets/rte.js");
            $this->modx->controller->addHtml(<<<HTML
<script>
{$class}
{$fields}
</script>
HTML
            );

            $result = $this->modx->invokeEvent('OnRichTextEditorInit', $options);
            if (!empty($result)) {
                if (is_array($result)) {
                    $result = implode('', $result);
                }
                $this->modx->controller->addHtml($result);
            }
            $rte->loadOverrides();
        }
    }

    /**
     * Convenient method to support RTE settings overrides for the component
     *
     * @param string $key The system setting key to grab, without any prefix
     * @param null $default An optional default value
     *
     * @return mixed|string The setting value, if any
     */
    public function getSetting($key, $default = null)
    {
        $cmpKey = "{$this->config['namespace']}.{$this->rtePrefix}{$key}";
        $setting = $this->modx->getOption(
            $cmpKey,
            null,
            $this->modx->getOption($cmpKey, $this->options, $default)
        );
        // Check if string means 'no value'
        if ($setting === $this->config['empty_setting_value']) {
            return '';
        }
        if (!$setting) {
            $setting = $this->getDefaultSetting($key);
        }

        return $setting;
    }

    /**
     * Get default system setting for the editor
     *
     * @param string $key The setting key
     *
     * @return mixed The setting value, if any
     */
    protected function getDefaultSetting($key)
    {
        return $this->modx->getOption($this->rtePrefix . $key);
    }

    /**
     * Get the RTE setting keys prefix
     *
     * @return string
     */
    protected function getRTEPrefix()
    {
        $defaultPrefix = '';
        switch ($this->editor) {
            case 'TinyMCE':
                $defaultPrefix = 'tiny.';
                break;
            case 'TinyMCERTE':
                $defaultPrefix = 'tinymcerte.';
                break;
            case 'CKEditor':
                $defaultPrefix = 'ckeditor.';
                break;
            case 'Redactor':
                $defaultPrefix = 'redactor.';
                break;
        }

        return $defaultPrefix;
    }
}
