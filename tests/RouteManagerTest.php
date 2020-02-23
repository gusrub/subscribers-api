<?php

namespace Tests;

use GuzzleHttp\Client;
use SubscribersApi\RouteManager as RouteManager;

/**
* Contains routing tests for the SubscribersApi\RouterManager.
*/
class RouteManagerTest extends BaseTestCase
{

    /**
     * HTTP client instance to send test requests
     */
    protected $httpClient;

    /**
     * Set ups each test case before actually running it
     */
    public function setUp()
    {
        parent::setUp();
        $this->httpClient = new \GuzzleHttp\Client([
            'base_uri' => $this->host,
            'exceptions' => false
        ]);
    }

    /**
     * Basic GET test for root route
     *
     * @return void
     * @test
     */
    public function rootRoute()
    {
        $response = $this->httpClient->get('/');
        $this->assertEquals(
            $response->getHeader('content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertEquals($response->getStatusCode(), "200");
    }

    /**
     * Subscribers resource index GET
     *
     * @return void
     * @test
     */
    public function subscribersIndex()
    {
        $response = $this->httpClient->get('/subscribers/1');
        $this->assertEquals(
            $response->getHeader('content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertEquals($response->getStatusCode(), "200");
    }

    /**
     * Subscribers specific resource GET
     *
     * @return void
     * @test
     */
    public function subscribersGet()
    {
        $response = $this->httpClient->get('/subscribers/1');
        $this->assertEquals(
            $response->getHeader('content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertEquals($response->getStatusCode(), "200");
    }

    /**
     * Subscribers resource POST
     *
     * @return void
     * @test
     */
    public function subscribersPost()
    {
        $response = $this->httpClient->post('/subscribers');
        $this->assertEquals(
            $response->getHeader('content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertEquals($response->getStatusCode(), "201");
    }

    /**
     * Subscribers resource PUT
     *
     * @return void
     * @test
     */
    public function subscribersPut()
    {
        $response = $this->httpClient->put('/subscribers/1');
        $this->assertEquals($response->getHeader(
            'content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertEquals($response->getStatusCode(), "200");
    }

    /**
     * Subscribers resource DELETE
     *
     * @return void
     * @test
     */
    public function subscribersDelete()
    {
        $response = $this->httpClient->delete('/subscribers/1');
        $this->assertEquals(
            $response->getHeader('content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertEquals($response->getStatusCode(), "204");
    }

    /**
     * Fields resource index GET
     *
     * @return void
     * @test
     */
    public function fieldsIndex()
    {
        $response = $this->httpClient->get('/subscribers/1/fields');
        $this->assertEquals(
            $response->getHeader('content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertEquals($response->getStatusCode(), "200");
    }

    /**
     * Fields specific resource GET
     *
     * @return void
     * @test
     */
    public function fieldsGet()
    {
        $response = $this->httpClient->get('/subscribers/1/fields/1');
        $this->assertEquals(
            $response->getHeader('content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertEquals($response->getStatusCode(), "200");
    }

    /**
     * Fields resource POST
     *
     * @return void
     * @test
     */
    public function fieldsPost()
    {
        $response = $this->httpClient->post('/subscribers/1/fields');
        $this->assertEquals(
            $response->getHeader('content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertEquals($response->getStatusCode(), "201");
    }

    /**
     * Fields resource PUT
     *
     * @return void
     * @test
     */
    public function fieldsPut()
    {
        $response = $this->httpClient->put('/subscribers/1/fields/1');
        $this->assertEquals($response->getHeader(
            'content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertEquals($response->getStatusCode(), "200");
    }

    /**
     * Fields resource DELETE
     *
     * @return void
     * @test
     */
    public function fieldsDelete()
    {
        $response = $this->httpClient->delete('/subscribers/1/fields/1');
        $this->assertEquals(
            $response->getHeader('content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertEquals($response->getStatusCode(), "204");
    }

    /**
     * Undefined route returns 404
     *
     * @return void
     * @test
     */
    public function anythingElse()
    {
        $response = $this->httpClient->get('/invalid');
        $this->assertEquals($response->getStatusCode(), "404");
    }
}
