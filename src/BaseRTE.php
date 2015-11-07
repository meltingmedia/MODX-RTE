<?php namespace Melting\MODX\RTE;

/**
 * Base class to extend to support additional RTEs
 *
 * @package meltingmedia\rte
 */
abstract class BaseRTE
{
    /**
     * A modX instance
     *
     * @var \modX
     */
    public $modx;
    /**
     * A Loader instance
     *
     * @var \Melting\MODX\RTE\Loader
     */
    public $rte;
    /**
     * @var null|string
     */
    protected $override = null;

    public function __construct(Loader $rte)
    {
        $this->rte =& $rte;
        $this->modx =& $this->rte->modx;
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
     *
     * @return array
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
            $this->modx->log(\modX::LOG_LEVEL_INFO, 'Not found');
            return;
        }
        $data = file_get_contents($override);
        $this->modx->controller->addHtml(<<<HTML
<script>
{$data}
</script>
HTML
        );
    }
}
