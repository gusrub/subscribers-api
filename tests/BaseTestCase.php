<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use SubscribersApi\Models\Campaign as Campaign;
use SubscribersApi\Models\Subscriber as Subscriber;
use SubscribersApi\Models\Field as Field;

/**
 * Generic functionality that all other tests could pontetially use.
 */
class BaseTestCase extends TestCase
{

    /**
     * Endpoint URL that these routes will be tested against
     */
    protected $host;

    /**
     * Set ups each test case before actually running it
     */
    public function setUp()
    {
        # load dotenv
        $env = getenv('SUBSCRIBERS_API_ENV');
        $dotenv = \Dotenv\Dotenv::createMutable(__DIR__ . "/../", ".env.$env");
        $dotenv->load();

        $host = getenv('BIND_ADDRESS');
        $port = getenv('BIND_PORT');
        $this->host = sprintf("http://%s:%s", $host, $port);

        // truncate all existing records, order is not important as we disable
        // fk checks
        $field = new Field();
        $field->truncate();

        $subscriber = new Subscriber();
        $subscriber->truncate();

        $campaign = new Campaign();
        $campaign->truncate();
    }

    /**
     * Executes actions after each test case is run.
     */
    public function tearDown()
    {

    }

    /**
     * Creates the given amount test objects for campaigns.
     *
     * @param int $amount The amount of records to create.
     * @return Array An array of campaign records.
     */
    protected function createCampaigns($amount)
    {
        $campaigns = [];

        for ($i=0; $i < $amount; $i++) {
            $campaign = new Campaign([
                "title" => "Test Campaign $i"
            ]);
            $campaign->save();
            array_push($campaigns, $campaign);
        }

        return $campaigns;
    }

    /**
     * Creates the given amount test obejcts for subscribers.
     *
     * @param int $amount The amount of records to create.
     * @param int $campaignId The parent campaign id of the subscribers. If null
     *  it will create new campaigns for each.
     * @return Array An array of subscribers records.
     */
    protected function createSubscribers($amount, $campaignId = null)
    {
        $subscribers = [];
        if (empty($campaignId)) {
            $campaign = $this->createCampaigns(1)[0];
            $campaignId = $campaign->id;
        }

        for ($i=0; $i < $amount; $i++) {
            $subscriber = new Subscriber([
                "fullName" => "John Doe $i",
                "email" => "john$i@example.com",
                "status" => "active",
                "campaignId" => $campaignId
            ]);
            $subscriber->save();
            array_push($subscribers, $subscriber);
        }

        return $subscribers;
    }

    /**
     * Creates the given amount test obejcts for subscribers.
     *
     * @param int $amount The amount of records to create.
     * @param int $subscriberId The parent subscriber id for these fields. If
     * null it will create new subscribers for each.
     * @param int $campaignId The parent campaign id of the subscribers. If null
     *  it will create new campaigns for each.
     * @return Array An array of fields records.
     */
    protected function createFields($amount, $subscriberId = null, $campaignId = null)
    {
        $fields = [];
        if (empty($subscriberId)) {
            $subscriber = $this->createSubscribers(1, $campaignId)[0];
            $subscriberId = $subscriber->id;
        }

        for ($i=0; $i < $amount; $i++) {
            $field = new Field([
                "title" => "Test Field $i",
                "dataType" => "string",
                "subscriberId" => $subscriberId
            ]);
            $field->save();
            array_push($fields, $field);
        }

        return $fields;
    }
}
