<?php
declare(strict_types=1);

namespace WernerDweight\DoctrineXIncludeXmlDriverBundle\DependencyInjection\Compiler;

use Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use WernerDweight\DoctrineXIncludeXmlDriverBundle\Doctrine\Driver\XmlDriver;
use WernerDweight\RA\RA;

/**
 * This compiler pass replaces the default XML driver with an XInclude-capable one.
 */
class DoctrineXIncludeXmlDriverCompilerPass implements CompilerPassInterface
{
    /** @var string */
    private const DEFAULT_XML_METADATA_DRIVER_SERVICE = 'doctrine.orm.default_xml_metadata_driver';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $originalDefinition = $container->findDefinition(self::DEFAULT_XML_METADATA_DRIVER_SERVICE);
        $container->setDefinition(self::DEFAULT_XML_METADATA_DRIVER_SERVICE, new Definition(
            XmlDriver::class,
            $originalDefinition->getArguments()
        ));
    }
}
