DoctrineXIncludeXmlDriverBundle
====================================

Symfony bundle providing XInclude support for XML mapping of Doctrine.

[![Build Status](https://travis-ci.org/wernerdweight/DoctrineXIncludeXmlDriverBundle.svg?branch=master)](https://travis-ci.org/wernerdweight/DoctrineXIncludeXmlDriverBundle)
[![Latest Stable Version](https://poser.pugx.org/wernerdweight/doctrine-xinclude-xml-driver-bundle/v/stable)](https://packagist.org/packages/wernerdweight/doctrine-xinclude-xml-driver-bundle)
[![Total Downloads](https://poser.pugx.org/wernerdweight/doctrine-xinclude-xml-driver-bundle/downloads)](https://packagist.org/packages/wernerdweight/doctrine-xinclude-xml-driver-bundle)
[![License](https://poser.pugx.org/wernerdweight/doctrine-xinclude-xml-driver-bundle/license)](https://packagist.org/packages/wernerdweight/doctrine-xinclude-xml-driver-bundle)


Installation
------------

### 1. Download using composer

```bash
composer require wernerdweight/doctrine-xinclude-xml-driver-bundle
```

### 2. Enable the bundle

Enable the bundle in your kernel:

```php
    <?php
    // config/bundles.php
    return [
        // ...
        WernerDweight\DoctrineXIncludeXmlDriverBundle\DoctrineXIncludeXmlDriverBundle::class => ['all' => true],
    ];
```

Configuration
------------

No configuration is required. This bundle automatically replaces the default XML driver with an XInclude-capable XML driver. 

Usage
------------

Add XInclude xmlns to the `doctrine-mapping` tag and you can then use `xi:include` tag to include mapping from other XML files. You can use `xpointer` to specify included parts of the linked XML file (see [xpointer](https://www.w3.org/TR/2003/REC-xptr-framework-20030325/) or browse [stack overflow](https://stackoverflow.com/search?q=xpointer)). 

```xml
<!-- SomeEntity.orm.xml -->
<?xml version="1.0" encoding="utf-8"?>
<doctrine-mapping xmlns:xi="http://www.w3.org/2001/XInclude" ...>
    <entity name="SomeEntity">
        <xi:include href="Deletable.trait.xml" parse="xml" xpointer="xpointer(//entity/*)"/>

        ...
    </entity>
</doctrine-mapping>

<!-- Deletable.trait.xml -->
<?xml version="1.0" encoding="utf-8"?>
<entity>
    <field name="deletedAt" type="datetime" />
    <many-to-one field="deletedBy" target-entity="User">
        <join-columns>
            <join-column name="deleted_by_id" referenced-column-name="id"/>
        </join-columns>
    </many-to-one>
</entity>
```

License
-------
This bundle is under the MIT license. See the complete license in the root directiory of the bundle.
