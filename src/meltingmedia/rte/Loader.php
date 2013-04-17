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
        $rte = $this->editor;
        $cmpKey = $this->config['namespace'] . '.' . $key;
        $setting = $this->modx->getOption($cmpKey, $default);

        if ($setting == 'none') return '';
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
