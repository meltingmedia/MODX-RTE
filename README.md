# MODX RTE

> A small library to help load RTEs in your MODX Revolution Custom Manager Pages

The purpose of this library is to help load RTEs in your Custom Manager Page, with the ability to switch from any RTE 
to another without code change.

It also ships some "enhancements" to existing RTEs, like :

* being able to set a value on the original field while keeping the RTE in sync (the RTE being some kind of "overlay" 
  to the original field)
* being able to use `focus()` on the original field, which will result in focusing the RTE if loaded
* provide a `MODx.unloadRTE()` function (when not provided by the RTE integration), to be able to "remove" the RTE 
  overlay


## Requirements

* MODX Revolution
* PHP 5.4+


## Installation

The easiest way to install the library is to make use of Composer :

    {
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/meltingmedia/MODX-RTE.git"
            }
        ],
        "require": {
            "melting/modx/rte": "^0.2"
        },
        "minimum-stability": "dev"
    }



## Usage

In your manager controller, simply run

    $loader = new \Melting\MODX\RTE\Loader(
        $this->modx,
        [
            'namespace' => 'some-prefix',
        ]
    );
    $loader->load();

This will load the configured RTE for your CMP.

Then, in your ExtJS code, you just need to `MODx.loadRTE(field.id)` on all fields in need of an RTE.


## Namespaces

Namespace serves to allow custom RTE configuration. They have nothing to do with MODX namespaces (modNamespace).

Just create a setting with key `{$namespace}.{$original_rte_setting_key}` to override `original_rte_setting_key` value.
If `{$namespace}.{$original_rte_setting_key}` setting is not found, it will fall back to the `original_rte_setting_key` value.

ie. TinyMCE `tiny.css_selectors` setting could be overridden by creating a `some-namespace.tiny.css_selectors` setting.


## Changes

See [change log](CHANGELOG.md).


## License

MODX RTE is licensed under the [MIT license](LICENSE).
