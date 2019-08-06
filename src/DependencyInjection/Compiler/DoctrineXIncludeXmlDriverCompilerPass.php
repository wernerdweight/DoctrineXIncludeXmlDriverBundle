<?php
declare(strict_types=1);

namespace WernerDweight\DoctrineXIncludeXmlDriverBundle\DependencyInjection\Compiler;

use Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use WernerDweight\DoctrineXIncludeXmlDriverBundle\Doctrine\Driver\XIncludeXmlDriver;
use WernerDweight\RA\RA;

class DoctrineXIncludeXmlDriverCompilerPass implements CompilerPassInterface
{
    /** @var string */
    private const DEFAULT_METADATA_DRIVER_SERVICE = 'doctrine.orm.default_metadata_driver';
    /** @var string */
    private const DEFAULT_XML_METADATA_DRIVER_SERVICE = 'doctrine.orm.default_xml_metadata_driver';
    /** @var string */
    private const ADD_DRIVER_METHOD = 'addDriver';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $driverChainDef = $container->findDefinition(self::DEFAULT_METADATA_DRIVER_SERVICE);

        $calls = (new RA($driverChainDef->getMethodCalls(), RA::RECURSIVE))
            ->map(function (RA $call): RA {
                if ($call->first() === self::ADD_DRIVER_METHOD) {
                    $originalReferenceSet = $call->last();
                    if ($originalReferenceSet instanceof RA) {
                        /** @var Reference $originalReference */
                        $originalReference = $originalReferenceSet->first();
                        if ((string)$originalReference === self::DEFAULT_XML_METADATA_DRIVER_SERVICE) {
                            $driver = new Definition(
                                XIncludeXmlDriver::class,
                                [[$originalReferenceSet->last()]]
                            );
                            $originalReferenceSet->set(0, $driver);
                        }
                    }
                }
                return $call;
            });
        $driverChainDef->setMethodCalls($calls->toArray(RA::RECURSIVE));
    }
}
