# Glob finder

[![Build Status](https://secure.travis-ci.org/doctrine/Glob.png)](http://travis-ci.org/doctrine/Glob)
[![Latest Stable Version](https://poser.pugx.org/doctrine/glob/version.png)](https://packagist.org/packages/doctrine/glob)
[![Total Downloads](https://poser.pugx.org/doctrine/glob/d/total.png)](https://packagist.org/packages/doctrine/glob)

This library provides a way of querying for objects from hierachical data
stores.

Currently support is only provided for [PHPCR](https://phpcr.github.io) and
[PHPCR-ODM](http://docs.doctrine-project.org/projects/doctrine-phpcr-odm/en/latest/)

````php
$documentManager = // get doctrine phpcr-odm document manager

$finder = new PhpcrOdmTraversalFinder($documentManager);
$finder->find('/cmf/articles/*');
````
