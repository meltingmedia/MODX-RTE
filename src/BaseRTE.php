<?php namespace meltingmedia\modx\rte;

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
     * @var \meltingmedia\modx\rte\Loader
     */
    public $rte;

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
}
