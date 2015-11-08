# Changelog

Changes for MODX RTE library


## [unreleased] (v0.2) xx/11/2015

* [Breaking change] It's now possible to set RTE options in the loader (using `setRTEOptions` method), meaning the
  loader no more automatically "loads" the RTE, making the `load` method having to be manually called
* [Breaking change] Loader can now load a different RTE than the one configured in `which_editor` setting (making use 
  of namespace prefix)
* [Breaking change] RTE settings/configuration are now "prefixed" with their RTE "name" (ie. `tiny.`, `ckeditor.`), to
  prevent "clashes"
* Added JS overrides to allow adding extra methods/features
* Added support for TinyMCE RTE (v4)
* Only care about PHP 5.4+
* Changed namespace to `Melting\MODX\RTE`


## [v0.1.2] - 06/09/2013

* Basic Redactor RTE support


## [v0.1.1] - 17/04/2013

* "Empty setting" key is now configurable
* Only try to load the RTE when supported


## v0.1.0 - 17/04/2013

* First internal "build"



[unreleased]: https://github.com/meltingmedia/modx-rte/compare/v0.1.2...HEAD
[v0.1.2]: https://github.com/meltingmedia/modx-rte/compare/v0.1.1...v0.1.2
[v0.1.1]: https://github.com/meltingmedia/modx-rte/compare/v0.1.0...v0.1.1
