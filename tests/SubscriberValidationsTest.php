<?php

namespace Tests;

use SubscribersApi\Models\Subscriber as Subscriber;

/**
 * Contains model validation tests for Subscriber
 */
class SubscriberValidationsTest extends BaseTestCase
{
    /**
     * The subject of the test
     */
    protected $subscriber;

    /**
     * Set ups each test case before actually running it
     */
    public function setUp()
    {
        $this->subscriber = new Subscriber([
            "fullName" => "Gustavo Rubio",
            "email" => "gus@ahivamos.net",
            "state" => "active",
            "campaignId" => 1
        ]);
    }

    /**
     * Test that email is required
     *
     * @return void
     * @test
     */
    public function requiresEmail()
    {
        $this->subscriber->email = null;
        $this->assertFalse($this->subscriber->valid());
    }

    /**
     * Test that fullName is required
     *
     * @return void
     * @test
     */
    public function requiresFullName()
    {
        $this->subscriber->fullName = null;
        $this->assertFalse($this->subscriber->valid());
    }

    /**
     * Test that fullName is required
     *
     * @return void
     * @test
     */
    public function requiresStatus()
    {
        $this->subscriber->status = null;
        $this->assertFalse($this->subscriber->valid());
    }

    /**
     * Test that fullName is required
     *
     * @return void
     * @test
     */
    public function requiresCampaignId()
    {
        $this->subscriber->campaignId = null;
        $this->assertFalse($this->subscriber->valid());
    }

    /**
     * Test that email format is valid
     *
     * @return void
     * @test
     */
    public function validEmailFormat()
    {
        $this->subscriber->email = "gustavo AT invalid";
        $this->assertFalse($this->subscriber->valid());
    }

    /**
     * Test that status is an accepted value
     *
     * @return void
     * @test
     */
    public function validStatus()
    {
        $this->subscriber->status = "invalid";
        $this->assertFalse($this->subscriber->valid());
    }
}
