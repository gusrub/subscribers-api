<?php

namespace Tests;

use SubscribersApi\Models\Subscriber as Subscriber;

/**
 * Contains model tests for Subscriber
 */
class SubscriberModelTest extends BaseTestCase
{
    /**
     * Test creation of subscriber
     *
     * @return void
     * @test
     */
    public function createSubscriber()
    {
        $campaign = $this->createCampaigns(1)[0];
        $subscriber = new Subscriber([
            "fullName" => "John Wayne",
            "email" => "john@example.com",
            "status" => "active",
            "campaignId" => $campaign->id
        ]);

        $this->assertTrue($subscriber->save());
        $this->assertNotEmpty(Subscriber::load($subscriber->id));
    }

    /**
     * Test update of subscriber through properties
     *
     * @return void
     * @test
     */
    public function updateSubscriberFromProperties()
    {
        $subscriber = $this->createSubscribers(1)[0];
        $subscriber->fullName = "New Name";
        $subscriber->email = "newemail@example.com";
        $subscriber->status = "unconfirmed";
        $subscriber->save();

        $subscriber = Subscriber::load($subscriber->id);

        $this->assertEquals($subscriber->fullName, "New Name");
        $this->assertEquals($subscriber->email, "newemail@example.com");
        $this->assertEquals($subscriber->status, "unconfirmed");
    }

    /**
     * Test update of subscriber through method
     *
     * @return void
     * @test
     */
    public function updateSubscriberFromMethod()
    {
        $subscriber = $this->createSubscribers(1)[0];
        $subscriber->save([
            "fullName" => "New Name",
            "email" => "newemail@example.com",
            "status" => "unconfirmed"
        ]);

        $subscriber = Subscriber::load($subscriber->id);

        $this->assertEquals($subscriber->fullName, "New Name");
        $this->assertEquals($subscriber->email, "newemail@example.com");
        $this->assertEquals($subscriber->status, "unconfirmed");
    }

    /**
     * Test update of subscriber through method
     *
     * @return void
     * @test
     */
    public function deleteSubscriber()
    {
        $subscriber = $this->createSubscribers(1)[0];
        $subscriber->delete();

        $this->assertEmpty(Subscriber::load($subscriber->id));
    }

    /**
     * Test list of subscribers
     *
     * @return void
     * @test
     */
    public function listSubscribers()
    {
        $this->createSubscribers(5);
        $subscribers = Subscriber::list();

        $this->assertEquals(count($subscribers), 5);
    }

    /**
     * Test list of subscribers with filters
     *
     * @return void
     * @test
     */
    public function listSubscribersWithFilter()
    {
        $campaign = $this->createCampaigns(2)[0];
        $this->createSubscribers(2);
        $this->createSubscribers(5, $campaign->id);
        $subscribers = Subscriber::list(["campaignId"=>$campaign->id]);

        $this->assertEquals(count($subscribers), 5);
    }
}
