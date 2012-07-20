<?php

namespace NoxLogic\Bundle\MultiParamBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class AddMultiParamConverterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('noxlogic_common_bundle.converter.manager')) {
            return;
        }

        $definition = $container->getDefinition('noxlogic_common_bundle.converter.manager');
        foreach ($container->findTaggedServiceIds('request.multi_param_converter') as $id => $attributes) {
            $definition->addMethodCall('add', array(new Reference($id), isset($attributes[0]['priority']) ? $attributes[0]['priority'] : 0));
        }
    }
}