<?php
declare(strict_types=1);

namespace WernerDweight\DoctrineXIncludeXmlDriverBundle\Doctrine\Driver;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use Doctrine\ORM\Mapping\MappingException;
use DOMDocument;
use SimpleXMLElement;

final class XmlDriver extends SimplifiedXmlDriver
{
    /**
     * @param string $file
     *
     * @return SimpleXMLElement
     *
     * @throws MappingException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function loadXml(string $file): SimpleXMLElement
    {
        $xmlDom = new DOMDocument();
        $xmlString = file_get_contents($file);
        if (false === $xmlString) {
            throw MappingException::mappingNotFound($file, $file);
        }
        $xmlDom->loadXML($xmlString);
        $xmlDom->documentURI = $file;
        $xmlDom->xinclude();

        $xmlElement = simplexml_import_dom($xmlDom);
        if (false === $xmlElement) {
            throw MappingException::mappingFileNotFound($file, $file);
        }
        return $xmlElement;
    }

    /**
     * @param string $file
     *
     * @return ClassMetadata[]
     *
     * @throws MappingException
     */
    protected function loadMappingFile($file)
    {
        $result = [];

        $xmlElement = $this->loadXml($file);

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
