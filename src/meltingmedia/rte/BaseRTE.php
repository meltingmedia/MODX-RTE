<?php namespace meltingmedia\rte;
/**
 * Base class to extend to support additional RTEs
 *
 * @package meltingmedia\rte
 */
abstract class BaseRTE
{
    /** @var \modX */
    public $modx;
    /** @var \meltingmedia\rte\Loader */
    public $rte;

    public function __construct(\meltingmedia\rte\Loader $rte)
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
    abstract function getOptions();
}
