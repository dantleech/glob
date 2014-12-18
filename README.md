# Glob finder

[![Build Status](https://secure.travis-ci.org/dantleech/glob.png)](http://travis-ci.org/dantleech/Glob)
[![Latest Stable Version](https://poser.pugx.org/dantleech/glob-finder/version.png)](https://packagist.org/packages/dantleech/glob-finder)
[![Total Downloads](https://poser.pugx.org/dantleech/glob-finder/d/total.png)](https://packagist.org/packages/dantleech/glob-finder)

This library provides a way of querying for objects from hierachical data
stores.

Currently support is only provided for [PHPCR](https://phpcr.github.io) and
[PHPCR-ODM](http://docs.dantleech-project.org/projects/dantleech-phpcr-odm/en/latest/)

````php
$documentManager = // get phpcr-odm document manager

$finder = new PhpcrOdmTraversalFinder($documentManager);
$finder->find('/cmf/articles/*');
````
