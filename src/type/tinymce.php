<?php namespace meltingmedia\rte\type;

class TinyMCE
{
    /** @var \modX */
    public $modx;
    /** @var \meltingmedia\rte */
    public $rte;

    public function __construct(\meltingmedia\rte $rte)
    {
        $this->rte =& $rte;
        $this->modx =& $this->rte->modx;

        $this->modx->log(\modX::LOG_LEVEL_INFO, 'in tiny loader');
    }
}
