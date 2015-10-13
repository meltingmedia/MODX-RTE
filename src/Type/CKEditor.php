<?php namespace Melting\MODX\RTE\Type;

use Melting\MODX\RTE\BaseRTE;

/**
 * CKEditor support
 */
class CKEditor extends BaseRTE
{
    /**
     * @inherit
     */
    public function getOptions()
    {
        return array(
//            'baseHref' => $this->getSetting(''),
//            'contentsCss' => $this->getSetting(''),
//            'language' => $this->getSetting(''),
            'skin' => $this->getSetting('skin'),
            'uiColor' => $this->getSetting('ui_color'),
            'toolbar' => $this->getSetting('toolbar'),
            'extraPlugins' => $this->getSetting('extra_plugins'),
            'disableObjectResizing' => $this->getSetting('object_resizing'),
//            'keystrokes' => $this->getSetting(''),
            'startupMode' => $this->getSetting('startup_mode'),
            'undoStackSize' => $this->getSetting('undo_size'),
        );
    }
}
