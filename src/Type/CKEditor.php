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
        return [
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
        ];
    }

    public function loadOverrides()
    {
        $this->modx->controller->addHtml(<<<HTML
<script>
Ext.onReady(function() {
    // Force the original textarea value to be updated when the editor content changes
    var original = MODx.ux.CKEditor.replaceComponent.prototype.constructor;
    MODx.ux.CKEditor.replaceComponent = function(textArea) {
         /** @type {MODx.ux.CKEditor} */
        var field = original.call(this, textArea)
            /** @type {CKEDITOR.editor} */
            ,editor = field.editor;

        editor.on('change', function() {
            editor.updateElement();
        });

        return field;
    };
    // Override to override the field focus method
    var loadrte = MODx.loadRTE.prototype.constructor;
    MODx.loadRTE = function(id) {
        loadrte.call(this, id);

        var field = Ext.getCmp(id);
        field.getRTE = function() {
            return MODx.loadedRTEs[this.id];
        }
        field.focus = function() {
            this.getRTE().editor.focus();
        };
    };
});
</script>
HTML
        );
    }
}
