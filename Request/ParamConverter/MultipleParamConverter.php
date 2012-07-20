<?php

namespace NoxLogic\Bundle\MultiParamBundle\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\DoctrineBundle\Registry;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Mapping\MappingException;

class MultiParamConverter implements ParamConverterInterface
{
    protected $registry;

    public function __construct(Registry $registry = null)
    {
        if (is_null($registry)) {
            return;
        }

        $this->registry = $registry;
    }

    public function apply(Request $request, ConfigurationInterface $configuration)
    {
        $class = $configuration->getClass();
        $options = $this->getOptions($configuration);

        // find by identifier?
        if (false === $object = $this->find($request, $configuration, $options)) {
            // find by criteria
            if (false === $object = $this->findOneBy($class, $request, $options)) {
                throw new \LogicException('Unable to guess how to get a Doctrine instance from the request information.');
            }
        }

        if (null === $object && false === $configuration->isOptional()) {
            throw new NotFoundHttpException(sprintf('%s object not found.', $class));
        }

        $request->attributes->set($configuration->getName(), $object);
    }

    protected function find(Request $request, ConfigurationInterface $configuration, $options)
    {
        $class = $configuration->getClass();

        $froms = array();
        $froms[] = $configuration->getFrom();
        if (isset($options['id'])) $froms[] = $options['id'];
        $froms[] = $configuration->getName()."_id";
        $froms[] = "id";

        $from = null;
        foreach ($froms as $from_entry) {
            if ($request->attributes->has($from_entry)) {
                $from = $from_entry;
                break;
            }
        }

        if (! $from) {
            return false;
        }

        $method = isset($options['method']) ? $options['method'] : "find";
        return $this->registry->getRepository($class, $options['entity_manager'])->$method($request->attributes->get($from));
    }

    protected function findOneBy($class, Request $request, $options)
    {
        $criteria = array();
        $metadata = $this->registry->getEntityManager($options['entity_manager'])->getClassMetadata($class);
        foreach ($request->attributes->all() as $key => $value) {
            if ($metadata->hasField($key)) {
                $criteria[$key] = $value;
            }
        }

        if (!$criteria) {
            return false;
        }

        return $this->registry->getRepository($class, $options['entity_manager'])->findOneBy($criteria);
    }

    public function supports(ConfigurationInterface $configuration)
    {
        if (null === $this->registry) {
            return false;
        }

        if (null === $configuration->getClass()) {
            return false;
        }

        $options = $this->getOptions($configuration);

        // Doctrine Entity?
        try {
            $this->registry->getEntityManager($options['entity_manager'])->getClassMetadata($configuration->getClass());

            return true;
        } catch (MappingException $e) {
            return false;
        }
    }

    protected function getOptions(ConfigurationInterface $configuration)
    {
        return array_replace(array(
            'entity_manager' => 'default',
        ), $configuration->getOptions());
    }
}
