<?php

namespace WireMock\Client;

use WireMock\Http\ResponseDefinition;
use WireMock\Stubbing\StubMapping;

class ServeEvent
{
    /** @var string */
    private $_id;
    /** @var LoggedRequest */
    private $_request;
    /** @var StubMapping */
    private $_stubMapping;
    /** @var ResponseDefinition */
    private $_responseDefinition;
    /** @var LoggedResponse */
    private $_response;

    /**
     * @param string $id
     * @param LoggedRequest $request
     * @param StubMapping $stubMapping
     * @param ResponseDefinition $responseDefinition
     * @param LoggedResponse $response
     */
    public function __construct(
        $id,
        LoggedRequest $request,
        StubMapping $stubMapping,
        ResponseDefinition $responseDefinition,
        LoggedResponse $response
    ) {
        $this->_id = $id;
        $this->_request = $request;
        $this->_stubMapping = $stubMapping;
        $this->_responseDefinition = $responseDefinition;
        $this->_response = $response;
    }

    /**
     * @param array $array
     * @return ServeEvent
     * @throws \Exception
     */
    public static function fromArray(array $array)
    {
        return new ServeEvent(
            $array['id'],
            LoggedRequest::fromArray($array['request']),
            StubMapping::fromArray($array['stubMapping']),
            ResponseDefinition::fromArray($array['responseDefinition']),
            LoggedResponse::fromArray($array['response'])
        );
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return LoggedRequest
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @return StubMapping
     */
    public function getStubMapping()
    {
        return $this->_stubMapping;
    }

    /**
     * @return ResponseDefinition
     */
    public function getResponseDefinition()
    {
        return $this->_responseDefinition;
    }

    /**
     * @return LoggedResponse
     */
    public function getResponse()
    {
        return $this->_response;
    }
}