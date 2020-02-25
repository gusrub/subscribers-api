<?php

namespace Tests;

use GuzzleHttp\Client;

/**
 * Contains the requests for subscribers.
 */
class SubscriberRequestTest extends BaseTestCase
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
     * Subscribers resource index GET
     *
     * @return void
     * @test
     */
    public function indexRequest()
    {
        $subscribers = $this->createSubscribers(5);

        $response = $this->httpClient->get("/subscribers");
        $body = json_decode($response->getBody());
        $this->assertEquals($response->getStatusCode(), "200");
        $this->assertCount(5, $body);
    }

    /**
     * Subscribers resource GET
     *
     * @return void
     * @test
     */
    public function getRequest()
    {
        $subscriber = $this->createSubscribers(1)[0];
        $subscriberId = $subscriber->id;

        $response = $this->httpClient->get("/subscribers/$subscriberId");
        $body = json_decode($response->getBody());
        $this->assertEquals($response->getStatusCode(), "200");
        $this->assertEquals($body->fullName, $subscriber->fullName);
        $this->assertEquals($body->email, $subscriber->email);
        $this->assertEquals($body->status, $subscriber->status);
        $this->assertEquals($body->campaignId, $subscriber->campaignId);
    }

    /**
     * Subscribers resource POST
     *
     * @return void
     * @test
     */
    public function postRequest()
    {
        $campaign = $this->createCampaigns(1)[0];
        $subscriberData = [
            "fullName" => "John Wayne",
            "email" => "john@example.com",
            "status" => "active",
            "campaignId" => $campaign->id
        ];
        $payload = json_encode($subscriberData);

        $response = $this->httpClient->post(
            "/subscribers",
            [
                "body" => $payload
            ]
        );
        $body = json_decode($response->getBody());
        $this->assertEquals($response->getStatusCode(), "201");
        $this->assertEquals($body->fullName, $subscriberData["fullName"]);
        $this->assertEquals($body->email, $subscriberData["email"]);
        $this->assertEquals($body->status, $subscriberData["status"]);
        $this->assertEquals($body->campaignId, $subscriberData["campaignId"]);
    }

    /**
     * Subscribers resource PUT
     *
     * @return void
     * @test
     */
    public function putRequest()
    {
        $subscriber = $this->createSubscribers(1)[0];
        $subscriberId = $subscriber->id;
        $subscriberData = [
            "fullName" => "New Name",
            "email" => "newemail@example.com",
            "status" => "bounced"
        ];
        $payload = json_encode($subscriberData);

        $response = $this->httpClient->put(
            "/subscribers/$subscriberId",
            [
                "body" => $payload
            ]
        );
        $body = json_decode($response->getBody());
        $this->assertEquals($response->getStatusCode(), "200");
        $this->assertEquals($body->fullName, $subscriberData["fullName"]);
        $this->assertEquals($body->email, $subscriberData["email"]);
        $this->assertEquals($body->status, $subscriberData["status"]);
    }

    /**
     * Subscribers resource delete
     *
     * @return void
     * @test
     */
    public function deleteRequest()
    {
        $subscriber = $this->createSubscribers(1)[0];
        $subscriberId = $subscriber->id;

        $response = $this->httpClient->delete("/subscribers/$subscriberId");
        $body = (string)$response->getBody();
        $this->assertEquals($response->getStatusCode(), "204");
        $this->assertEmpty($body);
    }

}