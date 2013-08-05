<?php namespace meltingmedia\rte;
/**
 * Service class "RTE Loader"
 *
 * @package meltingmedia\rte
 */
class Loader
{
    /** @var \modX  */
    public $modx;
    public $config = array();
    public $editor;

    public function __construct(\modX &$modx, array $options = array())
    {
        $this->modx =& $modx;
        $this->config = array_merge(array(
            'namespace' => null,
            'empty_setting_value' => 'none',
        ), $options);

        $this->editor = $this->modx->getOption('which_editor',null, null);
        if ($this->editor) $this->load();
    }

    /**
     * Returns an array of supported RTEs
     *
     * @return array The RTEs' names
     */
    public function getSupportedRTEs()
    {
        $supported = array();
        /** @var \DirectoryIterator $file */
        foreach(new \DirectoryIterator(dirname(__FILE__) . '/type/') as $file) {
            if ($file->isDot() || $file->isDir()) continue;
            $supported[] = rtrim($file->getFilename(), '.' . $file->getExtension());
        }

        return $supported;
    }

    public function load()
    {
        $supported = $this->getSupportedRTEs();
        if (!empty($this->editor) && in_array($this->editor, $supported)) {
            $editor = '\\meltingmedia\\rte\\type\\' . $this->editor;
            /** @var BaseRTE $rte */
            $rte = new $editor($this);
            $options = $rte->getOptions();

            $this->modx->invokeEvent('OnRichTextEditorInit', $options);
        }
    }

    /**
     * Get a setting for the current RTE
     *
     * @param string $key The setting key
     * @param mixed $default Optional default value
     *
     * @return mixed|string The setting value
     */
    public function getSetting($key, $default = null)
    {
        $cmpKey = $this->config['namespace'] . '.' . $key;
        $setting = $this->modx->getOption($cmpKey, null, $default);
        // Check if string mean 'no value'
        if ($setting == $this->config['empty_setting_value']) return '';
        if (!$setting) {
            $setting = $this->getDefaultSetting($key);
        }

        return $setting;
    }

    /**
     * Get the original RTE setting value
     *
     * @param string $key The setting key
     *
     * @return mixed The setting value
     */
    public function getDefaultSetting($key)
    {
        $defaultPrefix = null;
        switch ($this->editor) {
            case 'TinyMCE':
                $defaultPrefix = 'tiny.';
                break;
            case 'CKEditor':
                $defaultPrefix = 'ckeditor.';
                break;
        }

        return $this->modx->getOption($defaultPrefix . $key);
    }
}
