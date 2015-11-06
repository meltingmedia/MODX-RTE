# MODX RTE

> A small helper to help load RTEs in your MODX Revolution Custom Manager Pages


## Requirements

* MODX Revolution
* PHP 5.4+


## Usage

In the appropriate manager controller, run

    new \Melting\MODX\RTE\Loader(
        $this->modx,
        [
            'namespace' => 'some-prefix',
        ]
    );

This will load the configured RTE for your CMP.

Namespace serves to allow custom RTE configuration.
Just create a setting with key `{$namespace}.{$original_rte_setting_key}` to override `original_rte_setting_key` value.
If `{$namespace}.{$original_rte_setting_key}` setting is not found, it will fall back to the `original_rte_setting_key` value.

ie. TinyMCE `tiny.css_selectors` setting could be overridden by creating a `some-namespace.css_selectors`
