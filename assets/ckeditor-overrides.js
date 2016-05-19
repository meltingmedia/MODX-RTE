(function() {
    // Force the original textarea value to be updated when the editor content changes
    var original = MODx.ux.CKEditor.replaceComponent.prototype.constructor;
    MODx.ux.CKEditor.replaceComponent = function(textArea) {
        /** @type {MODx.ux.CKEditor} */
        var field = original.call(this, textArea)
        /** @type {CKEDITOR.editor} */
            ,editor = field.editor;

        // Make sure the original element (textarea) content/value stays in sync with the RTE
        // @TODO find a more clever way handle this, ie. only "sync" when the user stops typing
        editor.on('change', editor.updateElement, editor);

        return field;
    };

    // Override the CKEditor provided MODx.loadRTE so we could add some methods/data to fields with an RTE instance
    RTE.setOriginalMethod('ckeditor', MODx.loadRTE.prototype.constructor);
    MODx.loadRTE = function(id) {
        var field = Ext.getCmp(id);
        if (!field) {
            console.error('Field', id, 'not found');
            return;
        }
        RTE.callOriginalMethod('ckeditor', id);
        field.addEvents({
            rteLoaded: true
            ,rteUnloaded: true
        });
        field.rteLoaded = true;
        /**
         * Convenient method to retrieve the editor instance from the ExtJS field
         *
         * @returns {MODx.ux.CKEditor}
         */
        field.getRTE = function() {
            return MODx.loadedRTEs[this.id];
        };
        /**
         * Focus the editor
         */
        field.focus = function() {
            this.getRTE().editor.focus();
        };
        field.fireEvent('rteLoaded', field);
    };

    // Override the unloadRTE method so we could remove our stuff (extra method)
    var unloadRTE = MODx.unloadRTE.prototype.constructor;
    MODx.unloadRTE = function(id) {
        unloadRTE.call(this, id);

        var field = Ext.getCmp(id);
        if (!field) {
            return;
        }
        field.rteLoaded = false;
        field.focus = Ext.form.TextArea.prototype.focus;
        field.setValue = Ext.form.TextArea.prototype.setValue;
        field.getRTE = function() {};
        field.fireEvent('rteUnloaded', field);
    };
})
