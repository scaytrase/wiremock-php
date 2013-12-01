<?php

namespace WireMock\Client;

use WireMock\Matching\RequestPattern;
use WireMock\Matching\UrlMatchingStrategy;
use WireMock\Stubbing\StubMapping;

class WireMock
{
    /** @var string */
    private $_hostname;
    /** @var int */
    private $_port;
    /** @var HttpWait */
    private $_httpWait;
    /** @var Curl  */
    private $_curl;

    public static function create($hostname='localhost', $port=8080)
    {
        $httpWait = new HttpWait();
        $curl = new Curl();
        return new self($httpWait, $curl, $hostname, $port);
    }

    function __construct(HttpWait $httpWait, Curl $curl, $hostname='localhost', $port=8080)
    {
        $this->_hostname = $hostname;
        $this->_port = $port;
        $this->_httpWait = $httpWait;
        $this->_curl = $curl;
    }

    public function isAlive()
    {
        $url = $this->_makeUrl('__admin/');
        return $this->_httpWait->waitForServerToGive200($url);
    }

    public function stubFor(MappingBuilder $mappingBuilder)
    {
        $stubMapping = $mappingBuilder->build();
        $url = $this->_makeUrl('__admin/mappings/new');
        $this->_curl->post($url, $stubMapping->toArray());
        return $stubMapping;
    }

    public function verify(RequestPatternBuilder $requestPatternBuilder)
    {
        $requestPattern = $requestPatternBuilder->build();
        $url = $this->_makeUrl('__admin/requests/count');
        $responseJson = $this->_curl->post($url, $requestPattern->toArray());
        $response = json_decode($responseJson, true);
        if ($response['count'] < 1) {
            throw new VerificationException('Expected at least one request, but found ' . $response['count']);
        }
    }

    //TODO: findAll method

    public function reset()
    {
        $url = $this->_makeUrl('__admin/reset');
        $this->_curl->post($url);
    }

    private function _makeUrl($path)
    {
        return "http://$this->_hostname:$this->_port/$path";
    }

    /**
     * @param UrlMatchingStrategy $urlMatchingStrategy
     * @return MappingBuilder
     */
    public static function get(UrlMatchingStrategy $urlMatchingStrategy)
    {
        $requestPattern = new RequestPattern('GET', $urlMatchingStrategy);
        return new MappingBuilder($requestPattern);
    }

    //TODO: POST, PUT, DELETE, OPTIONS, HEAD, TRACE, ANY MappingBuilder methods

    /**
     * @param string $urlPath
     * @return UrlMatchingStrategy
     */
    public static function urlEqualTo($urlPath)
    {
        return new UrlMatchingStrategy('url', $urlPath);
    }

    //TODO: urlMatching

    //TODO: matching, notMatching, matchingJsonPath ValueMatchingStrategy methods

    /**
     * @param string $value
     * @return ValueMatchingStrategy
     */
    public static function equalTo($value)
    {
        return new ValueMatchingStrategy('equalTo', $value);
    }

    /**
     * @return ResponseDefinitionBuilder
     */
    public static function aResponse()
    {
        return new ResponseDefinitionBuilder();
    }

    /**
     * @param UrlMatchingStrategy $urlMatchingStrategy
     * @return RequestPatternBuilder
     */
    public static function getRequestedFor(UrlMatchingStrategy $urlMatchingStrategy)
    {
        return new RequestPatternBuilder('GET', $urlMatchingStrategy);
    }

    //TODO: Other [method]RequestedFor RequestPatternBuilder methods

    //TODO: setGlobalFixedDelay
    //TODO: addRequestProcessingDelay
}