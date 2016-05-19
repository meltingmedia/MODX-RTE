/**
 * A singleton help load RTE fields with their appropriate configuration
 */
var RTE = (function() {
    /**
     * @param {Ext.Container} cmp
     */
    function iterate(cmp) {
        if (MODx.config.use_editor && MODx.loadRTE) {
            // Handle MODX form panels
            var container = cmp.fp ? cmp.fp : cmp;
            var fields = container.find('rte', true);

            Ext.each(fields, function (field, idx, array) {
                var f = Ext.getCmp(field.id);
                if (!f.rendered) {
                    // Field not yet rendered, defer the RTE loading until then
                    f.on('render', function (field) {
                        MODx.loadRTE(field.id);
                    });
                } else {
                    MODx.loadRTE(field.id);
                }
            }, cmp);
        }
    }
    /**
     * The the given RTE "object" instance
     *
     * @param {String} rte
     *
     * @returns {Object}
     */
    function getRTE(rte) {
        if (rte === 'tinymcerte') {
            return TinyMCERTE;
        }
        if (rte === 'tinymce') {
            return Tiny;
        }
        if (rte === 'ckeditor') {
            return MODx.ux.CKEditor;
        }
    }
    /**
     * Get the object attribute name holding the configuration data for the given RTE
     *
     * @param {String} rte
     *
     * @returns {String}
     */
    function getRTEConfigKey(rte) {
        if (rte === 'tinymcerte') {
            return 'editorConfig';
        }
        if (rte === 'tinymce') {
            return 'config';
        }
        if (rte === 'ckeditor') {
            return 'editorConfig';
        }
    }
    /**
     * Convenient method to clone an object
     *
     * @param {Object} object
     *
     * @returns {Object}
     */
    function clone(object) {
        return JSON.parse(JSON.stringify(object));
    }
    /**
     * Set the RTE (global) configuration
     *
     * @param {String} rte
     * @param {Object} config
     */
    function setRTEConfig(rte, config) {
        var k = getRTE(rte);
        k[getRTEConfigKey(rte)] =  config;
    }
    /**
     * Get the given field configuration, if any
     *
     * @param {String} id
     *
     * @returns {null|Object}
     */
    function getFieldConfig(id) {
        return fields[id];
    }
    /**
     * Set the global configuration for the given field
     *
     * @param {string} rte
     * @param {string} id
     */
    function setConfig(rte, id) {
        setRTEConfig(rte, Ext.apply(clone(defaults[rte]), getFieldConfig(id)));
    }
    /**
     * Restore the original RTE configuration
     *
     * @param {String} rte
     */
    function restoreDefaults(rte) {
        setRTEConfig(rte, defaults[rte]);
    }

    /**
     * Original MODx.loadRTE function provided by the RTEs
     *
     * @type {Object}
     */
    var original = {};
    /**
     * An array of fields configuration
     *
     * @type {Object}
     */
    var fields = {};
    /**
     * Default RTEs configurations
     *
     * @type {Object}
     */
    var defaults = {};

    return {
        /**
         * Convenient method to find & initialize the RTEs (for fields having an attribute "rte" with a value of "true") for the given container
         *
         * @param {Ext.Container} cmp
         */
        loadRTEs: function(cmp) {
            if (cmp.rendered) {
                iterate(cmp);
            } else {
                cmp.on('afterrender', iterate);
            }
        }
        /**
         * Store the original MODx.loadRTE method, so we could restore/call it after overriding it for our needs
         *
         * @param {String} rte
         * @param callback
         */
        ,setOriginalMethod: function(rte, callback) {
            original[rte] = callback;
            // Store RTE default configuration (so we can handle per field configuration)
            defaults[rte] = clone(getRTE(rte)[getRTEConfigKey(rte)]);
        }
        /**
         * Call the original RTE MODx.loadRTE
         *
         * @param {String} rte
         * @param {String} id - The field ID on which we want to laod the RTE
         */
        ,callOriginalMethod: function(rte, id) {
            setConfig(rte, id);
            original[rte].call(this, id);
            restoreDefaults(rte);
        }
        /**
         * Set a specific configuration for the given field
         *
         * @param {String} id
         * @param {Object} config
         */
        ,setFieldConfig: function(id, config) {
            fields[id] = config;
        }
        ,loadOverrides: function(callback, attempts) {
            var maxAttempts = 10;
            if (!attempts) {
                attempts = 0;
            }
            if (MODx.loadRTE) {
                // RTE implementation of MODx.loadRTE is available, we can now safely apply our overrides
                callback();
            } else {
                if (attempts >= maxAttempts) {
                    console.log('Unable to find MODx.loadRTE, skipping the tries');
                    return false;
                }
                attempts++;
                var me = this;
                setTimeout(function() {
                    me.loadOverrides(callback, attempts);
                }, 100);
            }
        }
    }
})();
