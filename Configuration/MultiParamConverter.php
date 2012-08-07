<?php

namespace NoxLogic\Bundle\MultiParamBundle\Configuration;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;


/**
 * The MultiParamConverter class handles the @MultiParamConverter annotation parts.
 *
 * @Annotation
 */
class MultiParamConverter extends ConfigurationAnnotation
{

    /**
     * The parameter name.
     *
     * @var string
     */
    protected $name;

    /**
     * The parameter class.
     *
     * @var string
     */
    protected $class;

    /**
     * The routing parameter to convert from.
     *
     * @var string
     */
    protected $from = null;

    /**
     * An array of options.
     *
     * @var array
     */
    protected $options = array();

    /**
     * Whether or not the parameter is optional.
     *
     * @var Boolean
     */
    protected $optional = false;

    /**
     * Use explicitly named converter instead of iterating by priorities.
     *
     * @var string
     */
    protected $converter;

    /**
     * Returns the parameter name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the parameter name.
     *
     * @param string $name The parameter name
     */
    public function setValue($name)
    {
        $this->setName($name);
    }

    /**
     * Sets the parameter name.
     *
     * @param string $name The parameter name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the parameter class name.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Sets the parameter class name.
     *
     * @param string $class The parameter class name
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * Returns the routing parameter to convert from.
     *
     * @return string $from
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Sets the routing parameter to convert from.
     *
     * @param string $from The routing parameter
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * Returns an array of options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets an array of options.
     *
     * @param array $options An array of options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * Sets whether or not the parameter is optional.
     *
     * @param Boolean $optional Wether the parameter is optional
     */
    public function setIsOptional($optional)
    {
        $this->optional = (Boolean) $optional;
    }

    /**
     * Returns whether or not the parameter is optional.
     *
     * @return Boolean
     */
    public function isOptional()
    {
        return $this->optional;
    }

    /**
     * Get explicit converter name.
     *
     * @return string
     */
    public function getConverter()
    {
        return $this->converter;
    }

    /**
     * Set explicit converter name
     *
     * @param string $converter
     */
    public function setConverter($converter)
    {
        $this->converter = $converter;
    }

    /**
     * Returns the annotation alias name.
     *
     * @return string
     * @see ConfigurationInterface
     */
    public function getAliasName()
    {
        return 'multiparam_converters';
    }
}
