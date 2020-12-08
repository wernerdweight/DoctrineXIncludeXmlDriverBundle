<?php
declare(strict_types=1);

namespace WernerDweight\DoctrineXIncludeXmlDriverBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use WernerDweight\DoctrineXIncludeXmlDriverBundle\DependencyInjection\Compiler\DoctrineXIncludeXmlDriverCompilerPass;

class DoctrineXIncludeXmlDriverBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new DoctrineXIncludeXmlDriverCompilerPass());
    }
}
