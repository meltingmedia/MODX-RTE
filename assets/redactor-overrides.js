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
            /**
             * @returns {*}
             */
            field.getRTE = function() {
                return jQuery('#'+this.id);
            };
            /**
             * @param {*} value
             *
             * @returns {*}
             */
            field.setValue = function(value) {
                this.getRTE().redactor('code.set', value);
                return field.superclass().setValue.call(field, value);
            };
            field.focus = function() {
                this.getRTE().redactor('focus.setEnd');
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
                // Destroy the RTE "instance"
                jQuery('#' + id).redactor('core.destroy');
                // @TODO restore overridden methods
                //var field = Ext.getCmp(id);
                //field.focus = field.prototype.focus;
                //field.setValue = field.prototype.setValue;
            });
        }
    }
});
