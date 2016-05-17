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
     * Implement this method to return an array of configuration for the current RTE
     * The returned array will be passed to OnRichTextEditorInit event
     *
     * @return array|void
     */
    abstract public function getOptions();

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
var loadOverrides = function(callback, attempts) {
    var maxAttempts = 10;
    if (!attempts) {
        attempts = 0;
    }
    if (MODx.loadRTE) {
        // RTE implementation of MODx.loadRTE is available, we can now safely apply our overrides
        callback();
    } else {
        if (attempts >= maxAttempts) {
            console.log('Unable to find MODx.loadRTE, skipping the tries');
            return false;
        }
        attempts++;
        //console.log('deferred next attempt', attempts+'/'+ maxAttempts);
        setTimeout(function() {
            loadOverrides(callback, attempts);
        }, 100);
    }
};
loadOverrides({$data});
</script>
HTML
        );
    }
}
