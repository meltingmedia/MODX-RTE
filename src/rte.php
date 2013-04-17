<?php namespace meltingmedia;

use meltingmedia\rte\type\TinyMCE,
    meltingmedia\rte\type\CKEditor;

class rte
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
        if (!empty($this->editor)) {
            $options = new $this->editor($this);
            $test = new CKEditor($this);
            $testAgain = new TinyMCE($this);

            $this->modx->invokeEvent('OnRichTextEditorInit', $options);
        }
    }

    public function getSetting($key)
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
