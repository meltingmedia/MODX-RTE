<?php namespace Melting\MODX\RTE\Type;

use Melting\MODX\RTE\BaseRTE;

/**
 * New TinyMCE support
 */
class TinyMCERTE extends BaseRTE
{
    /**
     * @inherit
     */
    public function getOptions()
    {
        return [

        ];
    }

    public function loadOverrides()
    {
        $this->modx->controller->addHtml(<<<HTML
<script>
Ext.onReady(function() {
    var original = MODx.loadRTE.prototype.constructor;
    MODx.loadRTE = function(id) {
        original.call(this, id);
        var field = Ext.getCmp(id);
        field.setValue = function(value) {
            tinymce.get(field.id).setContent(value);
            return field.superclass().setValue.call(field, value);
        };
    };
    if (!MODx.unloadRTE) {
        // No method to unload an RTE instance, let's create it
        MODx.unloadRTE = function(id) {
            tinymce.get(id).remove();
        }
    }
});
</script>
HTML
        );
    }
}
