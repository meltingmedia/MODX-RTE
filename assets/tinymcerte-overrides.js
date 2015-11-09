Ext.onReady(function() {
    var original = MODx.loadRTE.prototype.constructor;
    MODx.loadRTE = function(id) {
        original.call(this, id);
        var field = Ext.getCmp(id);
        if (!field) {
            return;
        }
        field.addEvents({rteLoaded: true, rteUnloaded: true});
        // RTE might not be loaded yet, let's wait a little bit
        var setListener = function() {
            var editor = tinymce.get(id);
            if (!editor || !editor.on) {
                return;
            }
            field.editor = editor;
            field.rteLoaded = true;
            field.fireEvent('rteLoaded', field);
            // @TODO find a more clever way handle this, ie. only "sync" when the user stops typing
            editor.on('change', editor.save, editor);
        };
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
            this.getRTE().setContent(value);
            return field.superclass().setValue.call(field, value);
        };
        /**
         * When trying to focus the original field, focus the RTE
         */
        field.focus = function() {
            this.getRTE().focus(false);
        };
        Ext.defer(setListener, 250);
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
});
