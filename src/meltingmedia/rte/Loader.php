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
        switch ($rte) {
            case 'TinyMCE':
                $rtePrefix = 'tiny.';
                break;
            case 'CKEditor':
                $rtePrefix = 'ckeditor.';
                break;
        }
        $setting = $this->modx->getOption($cmpKey);
        $defaultKey = $rtePrefix . $key;

        if (!$setting) $setting = $this->modx->getOption($defaultKey);
        if ($setting == 'none') return '';

        return $setting;
    }
}
