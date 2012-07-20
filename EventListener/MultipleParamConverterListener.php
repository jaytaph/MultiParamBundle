<?php

namespace NoxLogic\Bundle\MultiParamBundle\EventListener;

use NoxLogic\Bundle\MultiParamBundle\Configuration\MultiParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class MultiParamConverterListener
{
    /**
     * @var Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterManager
     */
    protected $manager;

    /**
     * Constructor.
     *
     * @param ParamConverterManager $manager A ParamConverterManager instance
     */
    public function __construct(ParamConverterManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Modifies the MultiParamConverterManager instance.
     *
     * @param FilterControllerEvent $event A FilterControllerEvent instance
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        $request = $event->getRequest();
        $configurations = array();

        $configuration = $request->attributes->get('_multiparam_converters') ?: array();
        foreach ($configuration as $config) {
            $configurations[$config->getName()] = $config;
        }

        if (is_array($controller)) {
            $r = new \ReflectionMethod($controller[0], $controller[1]);
        } else {
            $r = new \ReflectionFunction($controller);
        }

        // automatically apply conversion for non-configured objects
        foreach ($r->getParameters() as $param) {
            if (!$param->getClass()) {
                continue;
            }

            $name = $param->getName();

            if ($request->attributes->has($name)) {
                // the parameter is already set, so disable the conversion
                unset($configurations[$name]);
            } else {
                if (isset($configurations[$name])) {
                    $configuration = $configurations[$name];
                    if ($configuration->getClass() == null) {
                        $class = $param->getClass()->getName();
                        if ($class == null) {
                            continue;
                        }
                        $configuration->setClass($class);
                    }
                } else {
                    $configuration = new MultiParamConverter(array());
                    $configuration->setName($name);
                    $configuration->setClass($param->getClass()->getName());
                }
                $configuration->setIsOptional($param->isOptional());

                $configurations[$name] = $configuration;
            }
        }

        $this->manager->apply($request, array_values($configurations));
    }
}
