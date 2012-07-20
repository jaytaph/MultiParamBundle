<?php

namespace NoxLogic\Bundle\MultiParamBundle;

use NoxLogic\Bundle\MultiParamBundle\DependencyInjection\Compiler\AddMultiParamConverterPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NoxLogicMultiParamBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AddMultiParamConverterPass());
    }
}
