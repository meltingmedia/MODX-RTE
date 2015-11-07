Ext.onReady(function() {
    // Force the original textarea value to be updated when the editor content changes
    var original = MODx.ux.CKEditor.replaceComponent.prototype.constructor;
    MODx.ux.CKEditor.replaceComponent = function(textArea) {
        /** @type {MODx.ux.CKEditor} */
        var field = original.call(this, textArea)
        /** @type {CKEDITOR.editor} */
            ,editor = field.editor;

        // Make sure the original element (textarea) content/value stays in sync with the RTE
        editor.on('change', editor.updateElement, editor);

        return field;
    };

    // Override the CKEditor provided MODx.loadRTE so we could add some methods/data to fields with an RTE instance
    var loadRTE = MODx.loadRTE.prototype.constructor;
    MODx.loadRTE = function(id) {
        loadRTE.call(this, id);

        var field = Ext.getCmp(id);
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
    };
});
