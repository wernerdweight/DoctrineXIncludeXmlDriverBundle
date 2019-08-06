<?php
declare(strict_types=1);

namespace WernerDweight\DoctrineXIncludeXmlDriverBundle\Doctrine\Driver;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use Doctrine\ORM\Mapping\MappingException;

final class XIncludeXmlDriver extends SimplifiedXmlDriver
{
    /**
     * @param string $file
     * @return array|ClassMetadata[]
     * @throws MappingException
     */
    protected function loadMappingFile($file)
    {
        $result = [];

        $xmlDom = new \DOMDocument();
        $xmlString = file_get_contents($file);
        if ($xmlString === false) {
            throw MappingException::mappingNotFound($file, $file);
        }
        $load = $xmlDom->loadXML($xmlString);
        $xmlDom->documentURI = $file;
        $xmlDom->xinclude();

        $xmlElement = simplexml_import_dom($xmlDom);

        // the following code is taken over from parent class
        if (isset($xmlElement->entity)) {
            foreach ($xmlElement->entity as $entityElement) {
                $entityName = (string)$entityElement['name'];
                $result[$entityName] = $entityElement;
            }
        } elseif (isset($xmlElement->{'mapped-superclass'})) {
            foreach ($xmlElement->{'mapped-superclass'} as $mappedSuperClass) {
                $className = (string)$mappedSuperClass['name'];
                $result[$className] = $mappedSuperClass;
            }
        } elseif (isset($xmlElement->embeddable)) {
            foreach ($xmlElement->embeddable as $embeddableElement) {
                $embeddableName = (string)$embeddableElement['name'];
                $result[$embeddableName] = $embeddableElement;
            }
        }

        return $result;
    }
}
