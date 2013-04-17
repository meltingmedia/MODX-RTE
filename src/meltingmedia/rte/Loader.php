<?php namespace meltingmedia\rte;

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

    public function load()
    {
        if (!empty($this->editor) && 'None' != $this->editor) {
            $editor = '\\meltingmedia\\rte\\type\\' . $this->editor;
            /** @var BaseRTE $rte */
            $rte = new $editor($this);
            $options = $rte->getOptions();

            $this->modx->invokeEvent('OnRichTextEditorInit', $options);
        }
    }

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
