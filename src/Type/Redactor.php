<?php namespace Melting\MODX\RTE\Type;

use Melting\MODX\RTE\BaseRTE;

/**
 * Redactor support
 */
class Redactor extends BaseRTE
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
    /**
     * @param {String|Array} elements
     */
    MODx.loadRTE = function(elements) {
        original.call(this, elements);
        if (!Ext.isArray(elements)) {
            elements = [elements];
        }
        Ext.each(elements, function(id) {
            var field = Ext.getCmp(id);
            field.setValue = function(value) {
                jQuery('#'+field.id).redactor('code.set', value);
                return field.superclass().setValue.call(field, value);
            };
        })
    };
    if (!MODx.unloadRTE) {
        // No unloadRTE method, let's create it
        MODx.unloadRTE = function(elements) {
            if (!Ext.isArray(elements)) {
                elements = [elements];
            }
            Ext.each(elements, function(id) {
                jQuery('#' + id).redactor('core.destroy');
            });
        }
    }
});
</script>
HTML
        );
    }
}
