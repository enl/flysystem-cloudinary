# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.2.0] - 2020-06-17

* **Changed** bump phpunit version to ^6
* **Changed** drop PHP 5 support
* **Fix** Deprecation errors in PHP 7.4

## [1.1.1] - 2018-02-21

* **Added** `GetVersionedUrl` plugin to get URL of specific file version 
(or latest one if no version provided)
* **Added** `setDeleteOptions` function for `ApiFacade` class which is necessary to pass options 
to during `delete` method execution: for example you can pass `invalidate` option 
to Cloudinary API to force cache invalidation.

## [1.1.0] - 2018-01-27

* **Added** ReadTransformation and GetUrl plugins for Flysystem to utilize more Cloudinary features
* **Added** PathConverterInterface to make it possible to implement 
your own logic in path to public id conversions

* **Fix** TypeError on listContents of non-existent folder


## [1.0.1] - 2016-04-26

* **Fix** `normalizeMetadata` method to handle `\ArrayObject` and regular arrays

## [1.0.0] - 2015-12-18

Initial release.
