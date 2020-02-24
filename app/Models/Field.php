<?php

namespace SubscribersApi\Models;

/**
 * Represents a data model for a field
 */
class Field extends BaseModel
{

    /**
     * Defines data types for fields
     */
    const DATA_TYPES = [
        "date",
        "number",
        "string",
        "boolean"
    ];

    /**
     * @var String The title/name of the field
     */
    public $title;

    /**
     * @var String Data type of this field
     */
    public $dataType;

    /**
     * @var int The subscriber ID that this field belongs to
     */
    public $subscriberId;

    /**
     * Overrides the data type to set it to an internal integer
     *
     * @param String $property The property name that is being accessed.
     * @return mixed The modified propery value
     */
    public function __get($property)
    {
        if ($property == 'dataType') {
            return self::DATA_TYPES[$this->dataType];
        }
    }

    /**
     * Overrides the data type to set it to an internal integer
     *
     * @param String $property The property name that is being accessed.
     * @param mixed $value The value that is being received to be set.
     * @return null
     */
    public function __set($property, $value)
    {
        if ($property == 'dataType') {
            $this->dataType = array_flip(self::DATA_TYPES)[$value];
        }
    }

    /**
     * Implements specific validations for a subscriber field.
     *
     * @return boolean Whether the model data is valid or not.
     */
    public function valid()
    {
        if (empty($this->title)) {
            $this->errors["title"] = "title is required";
        }
        if (empty($this->dataType)) {
            $this->errors["dataType"] = "dataType is required";
        }
        if (empty($this->subscriberId)) {
            $this->errors["subscriberId"] = "subscriberId is required";
        }

        if (in_array($this->dataType, self::DATA_TYPES) !== true) {
            $this->errors["dataType"] = sprintf(
                "invalid data type, must be any of %s",
                implode(", ", self::DATA_TYPES)
            );
        }

        return empty($this->errors);
    }

    /**
     * Defines the fields that the user is allowed to set.
     *
     * @return array The list of fields that the user may set.
     */
    protected function allowedFields()
    {
        return ["title", "dataType", "subscriberId", "campaignId"];
    }
}
