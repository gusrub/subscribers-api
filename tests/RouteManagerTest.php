<?php

namespace Tests;

use GuzzleHttp\Client;
use SubscribersApi\RouteManager as RouteManager;
use SubscribersApi\Models\Campaign as Campaign;
use SubscribersApi\Models\Subscriber as Subscriber;
use SubscribersApi\Models\Field as Field;

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
        $this->assertNotEquals($response->getStatusCode(), "404");
    }

    /**
     * Subscribers resource index GET
     *
     * @return void
     * @test
     */
    public function subscribersIndex()
    {
        $subscribers = $this->createSubscribers(5);

        $response = $this->httpClient->get("/subscribers");
        $this->assertEquals(
            $response->getHeader('content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertNotEquals($response->getStatusCode(), "404");
    }

    /**
     * Subscribers specific resource GET
     *
     * @return void
     * @test
     */
    public function subscribersGet()
    {
        $subscriber = $this->createSubscribers(1)[0];
        $subscriberId = $subscriber->id;

        $response = $this->httpClient->get("/subscribers/$subscriberId");
        $this->assertEquals(
            $response->getHeader('content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertNotEquals($response->getStatusCode(), "404");
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
        $this->assertNotEquals($response->getStatusCode(), "404");
    }

    /**
     * Subscribers resource PUT
     *
     * @return void
     * @test
     */
    public function subscribersPut()
    {
        $subscriber = $this->createSubscribers(1)[0];
        $subscriberId = $subscriber->id;

        $response = $this->httpClient->put("/subscribers/$subscriberId");
        $this->assertEquals($response->getHeader(
            'content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertNotEquals($response->getStatusCode(), "404");
    }

    /**
     * Subscribers resource DELETE
     *
     * @return void
     * @test
     */
    public function subscribersDelete()
    {
        $subscriber = $this->createSubscribers(1)[0];
        $subscriberId = $subscriber->id;

        $response = $this->httpClient->delete("/subscribers/$subscriberId");
        $this->assertNotEquals($response->getStatusCode(), "404");
    }

    /**
     * Fields resource index GET
     *
     * @return void
     * @test
     */
    public function fieldsIndex()
    {
        $subscriber = $this->createSubscribers(1)[0];
        $subscriberId = $subscriber->id;
        $fields = $this->createFields(5, $subscriberId);

        $response = $this->httpClient->get("/subscribers/$subscriberId/fields");
        $this->assertEquals(
            $response->getHeader('content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertNotEquals($response->getStatusCode(), "404");
    }

    /**
     * Fields specific resource GET
     *
     * @return void
     * @test
     */
    public function fieldsGet()
    {
        $subscriber = $this->createSubscribers(1)[0];
        $subscriberId = $subscriber->id;
        $field = $this->createFields(1, $subscriberId)[0];
        $fieldId = $field->id;

        $response = $this->httpClient->get(
            "subscribers/$subscriberId/fields/$fieldId"
        );

        $this->assertEquals(
            $response->getHeader('content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertNotEquals($response->getStatusCode(), "404");
    }

    /**
     * Fields resource POST
     *
     * @return void
     * @test
     */
    public function fieldsPost()
    {
        $subscriber = $this->createSubscribers(1)[0];
        $subscriberId = $subscriber->id;

        $response = $this->httpClient->post("/subscribers/$subscriberId/fields");
        $this->assertEquals(
            $response->getHeader('content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertNotEquals($response->getStatusCode(), "404");
    }

    /**
     * Fields resource PUT
     *
     * @return void
     * @test
     */
    public function fieldsPut()
    {
        $subscriber = $this->createSubscribers(1)[0];
        $subscriberId = $subscriber->id;
        $field = $this->createFields(1, $subscriberId)[0];
        $fieldId = $field->id;

        $response = $this->httpClient->put(
            "/subscribers/$subscriberId/fields/$fieldId"
        );
        $this->assertEquals($response->getHeader(
            'content-type')[0],
            'application/json; charset=utf-8'
        );
        $this->assertNotEquals($response->getStatusCode(), "404");
    }

    /**
     * Fields resource DELETE
     *
     * @return void
     * @test
     */
    public function fieldsDelete()
    {
        $subscriber = $this->createSubscribers(1)[0];
        $subscriberId = $subscriber->id;
        $field = $this->createFields(1, $subscriberId)[0];
        $fieldId = $field->id;

        $response = $this->httpClient->delete(
            "/subscribers/$subscriberId/fields/$fieldId"
        );
        $this->assertNotEquals($response->getStatusCode(), "404");
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
