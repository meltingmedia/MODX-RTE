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

        $this->modx->log(\modX::LOG_LEVEL_INFO, 'loading ' . $this->rte->editor);

        return $this->getOptions();
    }

    protected function getSetting($key)
    {
        return $this->rte->getSetting($key);
    }

    abstract function getOptions();
}
