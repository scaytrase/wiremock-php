<?php

namespace WireMock\Client;

class JsonValueMatchingStrategy extends ValueMatchingStrategy
{
    private $_ignoreArrayOrder = null;
    private $_ignoreExtraElements = null;

    public function __construct($matchingValue, $ignoreArrayOrder = null, $ignoreExtraElements = null)
    {
        parent::__construct('equalToJson', $matchingValue);
        $this->_ignoreArrayOrder = $ignoreArrayOrder;
        $this->_ignoreExtraElements = $ignoreExtraElements;
    }

    public function toArray()
    {
        $array = parent::toArray();
        if ($this->_ignoreArrayOrder) {
            $array['ignoreArrayOrder'] = $this->_ignoreArrayOrder;
        }
        if ($this->_ignoreExtraElements) {
            $array['ignoreExtraElements'] = $this->_ignoreExtraElements;
        }
        return $array;
    }
}
