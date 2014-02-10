<?php namespace meltingmedia\rte;

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
    public $config = array();
    /**
     * The active editor name
     *
     * @var string|null
     */
    public $editor;

    public function __construct(\modX &$modx, array $options = array())
    {
        $this->modx =& $modx;
        $this->config = array_merge(array(
            'namespace' => null,
            'empty_setting_value' => 'none',
        ), $options);

        $this->editor = $this->modx->getOption('which_editor', null, null);
        if ($this->editor) {
            $this->load();
        }
    }

    /**
     * Iterate over supported RTEs classes
     *
     * @return array
     */
    public function getSupportedRTEs()
    {
        $supported = array();
        /** @type \DirectoryIterator $file */
        foreach (new \DirectoryIterator(dirname(__FILE__) . '/type/') as $file) {
            if ($file->isDot() || $file->isDir()) {
                continue;
            }
            $name = $file->getFilename();
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $supported[] = rtrim($name, '.' . $ext);
        }

        return $supported;
    }

    /**
     * Instantiate the appropriate RTE class
     *
     * @return void
     */
    public function load()
    {
        $supported = $this->getSupportedRTEs();
        if (!empty($this->editor) && in_array($this->editor, $supported)) {
            $editor = '\\meltingmedia\\rte\\type\\' . $this->editor;
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
        }
    }

    /**
     * Convenient method to support RTE overrides for the component
     *
     * @param string $key The system setting key to grab, without any prefix
     * @param null $default An optional default value
     *
     * @return mixed|string The setting value, if any
     */
    public function getSetting($key, $default = null)
    {
        $cmpKey = $this->config['namespace'] . '.' . $key;
        $setting = $this->modx->getOption($cmpKey, null, $default);
        // Check if string mean 'no value'
        if ($setting == $this->config['empty_setting_value']) {
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
        $defaultPrefix = null;
        switch ($this->editor) {
            case 'TinyMCE':
                $defaultPrefix = 'tiny.';
                break;
            case 'CKEditor':
                $defaultPrefix = 'ckeditor.';
                break;
            case 'Redactor':
                $defaultPrefix = 'redactor.';
                break;
        }

        return $this->modx->getOption($defaultPrefix . $key);
    }
}
