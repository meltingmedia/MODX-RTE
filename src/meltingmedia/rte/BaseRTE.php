<?php namespace meltingmedia\rte;

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

    protected function getSetting($key, $default = null)
    {
        return $this->rte->getSetting($key, $default);
    }

    abstract function getOptions();
}
