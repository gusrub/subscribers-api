<?php

namespace Tests;

use SubscribersApi\Models\Subscriber as Subscriber;
use SubscribersApi\Models\Field as Field;

/**
 * Contains model tests for Field
 */
class FieldModelTest extends BaseTestCase
{
    /**
     * Test creation of field
     *
     * @return void
     * @test
     */
    public function createField()
    {
        $subscriber = $this->createSubscribers(1)[0];
        $field = new Field([
            "title" => "Join Date",
            "dataType" => "date",
            "subscriberId" => $subscriber->id
        ]);

        $this->assertTrue($field->save());
        $this->assertNotEmpty(Field::load($field->id));
    }

    /**
     * Test update of field through properties
     *
     * @return void
     * @test
     */
    public function updateFieldFromProperties()
    {
        $field = $this->createFields(1)[0];
        $field->title = "new_title";
        $field->dataType = "boolean";
        $field->save();

        $field = Field::load($field->id);

        $this->assertEquals($field->title, "new_title");
        $this->assertEquals($field->dataType, "boolean");
    }

    /**
     * Test update of field through method
     *
     * @return void
     * @test
     */
    public function updateFieldFromMethod()
    {
        $field = $this->createFields(1)[0];
        $field->save([
            "title" => "new_title",
            "dataType" => "boolean"
        ]);

        $field = Field::load($field->id);

        $this->assertEquals($field->title, "new_title");
        $this->assertEquals($field->dataType, "boolean");
    }

    /**
     * Test update of field through method
     *
     * @return void
     * @test
     */
    public function deleteField()
    {
        $field = $this->createFields(1)[0];
        $field->delete();

        $this->assertEmpty(Field::load($field->id));
    }

    /**
     * Test list of fields
     *
     * @return void
     * @test
     */
    public function listFields()
    {
        $this->createFields(5);
        $fields = Field::list();

        $this->assertEquals(count($fields), 5);
    }

    /**
     * Test list of fields with filters
     *
     * @return void
     * @test
     */
    public function listFieldsWithFilter()
    {
        $subscriber = $this->createSubscribers(2)[0];
        $this->createFields(2);
        $this->createFields(5, $subscriber->id);
        $fields = Field::list(["subscriberId"=>$subscriber->id]);

        $this->assertEquals(count($fields), 5);
    }
}
