<?php namespace Melting\MODX\RTE;

/**
 * Base class to extend to support additional RTEs
 */
abstract class BaseRTE
{
    /**
     * A modX instance
     *
     * @var \modX
     */
    protected $modx;
    /**
     * A Loader instance
     *
     * @var \Melting\MODX\RTE\Loader
     */
    protected $rte;
    /**
     * @var null|string
     */
    protected $override = null;
    /**
     * The RTE MODX namespace
     *
     * @var string
     */
    protected $prefix = '';

    public function __construct(Loader $rte)
    {
        $this->rte = $rte;
        $this->modx = $this->rte->modx;
    }

    /**
     * Wrapper method to retrieve a setting for the current RTE
     *
     * @param string $key The setting key
     * @param mixed $default Optional default value
     *
     * @return mixed|string The setting value
     */
    protected function getSetting($key, $default = null)
    {
        return $this->rte->getSetting($key, $default);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getRTEKey($key)
    {
        $search = "{$this->rte->config['namespace']}.{$this->prefix}.";

        return str_replace($search, '', $key);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function isValidSetting($key)
    {
        return strpos($key, "{$this->rte->config['namespace']}.{$this->prefix}.") === 0;
    }

    /**
     * Implement this method to return an array of configuration for the current RTE
     * The returned array will be passed to OnRichTextEditorInit event
     *
     * @return array|void
     */
    public function getOptions()
    {
        $settings = $this->rte->getRTEOptions();
        $options = [];
        foreach ($settings as $k => $value) {
            if ($this->isValidSetting($k)) {
                $key = $this->getRTEKey($k);
                $this->modx->setOption("{$this->prefix}.{$k}", $value);
                $options[$key] = $value;
            }
        }

        return $options;
    }

    /**
     * A method to load some overrides, when needed, to tweak some RTEs, add some methods...
     *
     * @return void
     */
    public function loadOverrides()
    {
        if (!$this->override) {
            return;
        }
        $override = dirname(__DIR__) ."/assets/{$this->override}";
        if (!file_exists($override)) {
            $this->modx->log(\modX::LOG_LEVEL_INFO, __METHOD__ . ' override not found : '.$override);
            return;
        }
        $data = file_get_contents($override);
        $this->modx->controller->addHtml(<<<HTML
<script>
Ext.onReady(function() {
    RTE.loadOverrides({$data});
});
</script>
HTML
        );
    }
}
