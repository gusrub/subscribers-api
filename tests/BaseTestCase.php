<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

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
        $host = getenv('BIND_ADDRESS');
        $port = getenv('BIND_PORT');
        $this->host = sprintf("http://%s:%s", $host, $port);
    }

    /**
     * Executes actions after each test case is run.
     */
    public function tearDown()
    {

    }
}
