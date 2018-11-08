<?php

namespace Sherlockode\ConfigurationBundle;

use Sherlockode\ConfigurationBundle\DependencyInjection\Compiler\FieldTypePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SherlockodeConfigurationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new FieldTypePass());
    }
}
