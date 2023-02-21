<?php
declare(strict_types=1);

namespace WernerDweight\DoctrineXIncludeXmlDriverBundle\Doctrine\Driver;

use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\Mapping\ClassMetadata;
use DOMDocument;
use Safe\Exceptions\FilesystemException;
use Safe\Exceptions\SimplexmlException;
use SimpleXMLElement;

final class XmlDriver extends SimplifiedXmlDriver
{
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

    /**
     * @throws MappingException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function loadXml(string $file): SimpleXMLElement
    {
        $xmlDom = new DOMDocument();
        try {
            $xmlString = \Safe\file_get_contents($file);
        } catch (FilesystemException $exception) {
            throw MappingException::mappingNotFound($file, $file);
        }
        $xmlDom->loadXML($xmlString);
        $xmlDom->documentURI = $file;
        $xmlDom->xinclude();

        try {
            $xmlElement = \Safe\simplexml_import_dom($xmlDom);
        } catch (SimplexmlException $exception) {
            throw MappingException::mappingFileNotFound($file, $file);
        }
        return $xmlElement;
    }
}
