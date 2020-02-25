<?php

namespace SubscribersApi\Models;

/**
 * Represents a data model for a campaign
 */
class Campaign extends BaseModel
{

    /**
     * @var String The title/name of the campaign
     */
    public $title;

    /**
     * Implements specific validations for a campaign.
     *
     * @return boolean Whether the model data is valid or not.
     */
    public function valid()
    {
        $this->errors = [];

        if (empty($this->title)) {
            $this->errors["title"] = "title is required";
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
        return ["title"];
    }

    /**
     * Returns the table name that this model stores data into.
     */
    protected function getTableName()
    {
        return "campaigns";
    }
}
