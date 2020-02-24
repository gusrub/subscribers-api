<?php

namespace Tests;

use SubscribersApi\Models\Field as Field;

/**
 * Contains model validation tests for Field
 */
class FieldValidationsTest extends BaseTestCase
{
    /**
     * The subject of the test
     */
    protected $field;

    /**
     * Set ups each test case before actually running it
     */
    public function setUp()
    {
        $this->field = new Field([
            "title" => "First Name",
            "dataType" => "string",
            "subscriberId" => "subscriberId"
        ]);
    }

    /**
     * Test that title is required
     *
     * @return void
     * @test
     */
    public function requiresTitle()
    {
        $this->field->title = null;
        $this->assertFalse($this->field->valid());
    }

    /**
     * Test that data type is required
     *
     * @return void
     * @test
     */
    public function requiresDataType()
    {
        $this->field->dataType = null;
        $this->assertFalse($this->field->valid());
    }

    /**
     * Test that subscriberId is required
     *
     * @return void
     * @test
     */
    public function requiresSubscriberId()
    {
        $this->field->subscriberId = null;
        $this->assertFalse($this->field->valid());
    }

    /**
     * Test that data type is an accepted value
     *
     * @return void
     * @test
     */
    public function validDataType()
    {
        $this->field->dataType = "invalid";
        $this->assertFalse($this->field->valid());
    }
}
