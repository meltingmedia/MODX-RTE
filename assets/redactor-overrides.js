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
            if (!field) {
                return;
            }
            field.addEvents({rteLoaded: true, rteUnloaded: true});
            field.rteLoaded = true;
            /**
             * @returns {*}
             */
            field.getRTE = function() {
                return jQuery('#'+this.id);
            };
            /**
             * Update the ExtJS "field" setValue to "sync" data in the RTE too
             *
             * @param {*} value
             *
             * @returns {*}
             */
            field.setValue = function(value) {
                this.getRTE().redactor('code.set', value);
                return field.superclass().setValue.call(field, value);
            };
            /**
             * When trying to focus the original field, focus the RTE
             */
            field.focus = function() {
                this.getRTE().redactor('focus.setStart');
            };
            field.fireEvent('rteLoaded', field);
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

                var field = Ext.getCmp(id);
                if (!field) {
                    return;
                }
                field.rteLoaded = false;
                field.focus = Ext.form.TextArea.prototype.focus;
                field.setValue = Ext.form.TextArea.prototype.setValue;
                field.getRTE = function() {};
                field.fireEvent('rteUnloaded', field);
            });
        }
    }
});
