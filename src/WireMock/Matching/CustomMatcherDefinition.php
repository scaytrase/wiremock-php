<?php

namespace WireMock\Matching;

class CustomMatcherDefinition
{
    /** @var string */
    private $_name;
    /** @var array */
    private $_parameters;

    /**
     * @param string $name
     * @param array $parameters
     */
    public function __construct($name, array $parameters)
    {
        $this->_name = $name;
        $this->_parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    public function toArray()
    {
        return array(
            'name' => $this->_name,
            'parameters' => $this->_parameters
        );
    }

    public static function fromArray(array $array)
    {
        return new CustomMatcherDefinition(
            $array['name'],
            isset($array['parameters']) ? $array['parameters'] : array()
        );
    }
}