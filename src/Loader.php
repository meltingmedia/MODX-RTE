<?php namespace Melting\MODX\RTE;

use modX;

/**
 * A service to help loading the appropriate for our RTE
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
    public $editor;
    /**
     * RTE options defined
     *
     * @var array
     */
    protected $options = [];

    public function __construct(modX &$modx, array $options = [])
    {
        $this->modx =& $modx;
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
        $editor = $this->modx->getOption("{$this->config['namespace']}.which_editor", $this->options, null);
        if (!$editor || empty($editor)) {
            // No particular namespace editor found, let's fall back to the global one
            $editor = $this->modx->getOption('which_editor', null, null);
        } else {
            // We have an RTE defined, which is not the "default" system wide one (which_editor setting)
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
     * Iterate over supported RTEs classes
     *
     * @return array
     */
    public function getSupportedRTEs()
    {
        return [
            'CKEditor',
            'Redactor',
            'TinyMCE',
            'TinyMCERTE',
        ];
    }

    /**
     * Instantiate the appropriate RTE class
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
            $editor = '\\Melting\\MODX\\RTE\\Type\\' . $this->editor;
            /** @var BaseRTE $rte */
            $rte = new $editor($this);
            $options = $rte->getOptions();

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
        $cmpKey = "{$this->config['namespace']}.{$this->getRTEPrefix()}{$key}";
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
    public function getDefaultSetting($key)
    {
        return $this->modx->getOption($this->getRTEPrefix() . $key);
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
