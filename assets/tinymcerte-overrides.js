(function() {
    var rte = tinymce.onAddEditor ? 'tinymce' : 'tinymcerte';
    RTE.setOriginalMethod(rte, MODx.loadRTE.prototype.constructor);

    MODx.loadRTE = function(id) {
        var field = Ext.getCmp(id);
        if (!field) {
            console.error('Field', id, 'not found');
            return;
        }
        field.addEvents({
            rteLoaded: true
            ,rteUnloaded: true
        });

        // RTE might not be loaded yet, let's wait a little bit
        if (rte === 'tinymce') {
            // TinyMCE v3
            tinymce.onAddEditor.add(function(mgr, editor) {
                editor.onInit.add(function() {
                    setListener(0);
                });
            })
        } else {
            // TinyMCE v4
            tinymce.once('addEditor',function(vent) {
                vent.editor.once('init', function() {
                    setListener(0);
                });
            });
        }
        var setListener = function(attempts) {
            if (!attempts) {
                attempts = 0;
            }
            if (attempts > 10) {
                console.error('Stopping trying to set the listener since we were not able to find the editor');
                return;
            }
            attempts++;
            var editor = tinymce.get(id);
            if (!editor) {
                Ext.defer(function() {
                    setListener(attempts);
                }, 250);
                return;
            }
            field.editor = editor;
            field.rteLoaded = true;
            field.fireEvent('rteLoaded', field);
            if (editor.on) {
                // Only for TinyMCE RTE (v4)
                editor.on('change', editor.save, editor);
            }
        };
        RTE.callOriginalMethod(rte, id);
        /**
         * @returns {tinymce.Editor}
         */
        field.getRTE = function() {
            return tinymce.get(this.id);
        };
        /**
         * Update the ExtJS "field" setValue to "sync" data in the RTE too
         *
         * @param {*} value
         *
         * @returns {*}
         */
        field.setValue = function(value) {
            if (!this.rteLoaded) {
                this.on('rteLoaded', function() {
                    this.setValue(value);
                }, this, {
                    single: true
                });
                return;
            }
            this.getRTE().setContent(value);
            return field.superclass().setValue.call(field, value);
        };
        /**
         * When trying to focus the original field, focus the RTE
         */
        field.focus = function() {
            if (!this.rteLoaded) {
                this.on('rteLoaded', this.focus, this, {
                    single: true
                });
                return;
            }
            this.getRTE().focus(false);
        };
    };

    if (!MODx.unloadRTE) {
        // No method to unload an RTE instance, let's create it
        MODx.unloadRTE = function(id) {
            tinymce.get(id).remove();
            var field = Ext.getCmp(id);
            if (!field) {
                return;
            }
            field.rteLoaded = false;
            field.focus = Ext.form.TextArea.prototype.focus;
            field.setValue = Ext.form.TextArea.prototype.setValue;
            field.getRTE = function() {};
            field.fireEvent('rteUnloaded', field);
        }
    }
})
