(function() {
    // Override the redactor function so we could apply "per field" configuration
    var red = $red.fn.redactor;
    $red.fn.redactor = function(config) {
        Ext.apply(config, window['_redactor']);

        return red.call(this, config);
    };

    RTE.setOriginalMethod('redactor', MODx.loadRTE.prototype.constructor);
    /**
     * @param {String|Array} elements
     */
    MODx.loadRTE = function(elements) {
        RTE.callOriginalMethod('redactor', elements);
        if (!Ext.isArray(elements)) {
            elements = [elements];
        }
        Ext.each(elements, function(id) {
            var field = Ext.getCmp(id);
            if (!field) {
                return;
            }
            field.addEvents({
                rteLoaded: true
                ,rteUnloaded: true
            });
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
            // Some hack to handle having the RTE with a fixed height (no auto grow)
            if (field.height || field.width) {
                // Redactor RTE container
                var app = Ext.get(field.getRTE()[0].parentNode)
                    // RTE toolbar (if any)
                    ,toolbar = app.first('.redactor-toolbar')
                    // Editor content
                    ,editor = app.first('.redactor-editor');

                if (editor) {
                    if (field.height) {
                        var height = field.height;
                        if (toolbar) {
                            // A toolbar has been found, let's remove its height
                            height = height - toolbar.getHeight();
                        }
                        // Set the content editor height to the expected size!
                        editor.setStyle('height', height + 'px');
                        editor.setStyle('min-height', 0);
                    }
                    if (field.width) {
                        app.setStyle('width', field.width +'px');
                    }
                }
            }
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
})
