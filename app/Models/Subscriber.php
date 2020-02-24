<?php

namespace SubscribersApi\Models;

/**
 * Represents a data model for a subscriber
 */
class Subscriber extends BaseModel
{

    /**
     * Defines the possible status of a subscriber
     */
    const STATUSES = [
        "active",
        "unsubscribed",
        "junk",
        "bounced",
        "unconfirmed"
    ];

    /**
     * @var String The email address of the subscriber
     */
    public $email;

    /**
     * @var String Full name of the subscriber
     */
    public $fullName;

    /**
     * @var String Current status of the subscriber. Can be any value of STATUSES
     */
    public $status;

    /**
     * @var int The campaign ID for this subsciber
     */
    public $campaignId;

    /**
     * Overrides the status to set it to an internal integer
     *
     * @param String $property The property name that is being accessed.
     * @return mixed The modified propery value
     */
    public function __get($property)
    {
        if ($property == 'status') {
            return self::STATUSES[$this->status];
        }
    }

    /**
     * Overrides the status to set it to an internal integer
     *
     * @param String $property The property name that is being accessed.
     * @param mixed $value The value that is being received to be set.
     * @return null
     */
    public function __set($property, $value)
    {
        if ($property == 'status') {
            $this->status = array_flip(self::STATUSES)[$value];
        }
    }

    /**
     * Implements specific validations for a subscriber.
     *
     * @return boolean Whether the model data is valid or not.
     */
    public function valid()
    {
        if (empty($this->email)) {
            $this->errors["email"] = "email is required";
        }
        if (empty($this->fullName)) {
            $this->errors["fullName"] = "fullName is required";
        }
        if (empty($this->status)) {
            $this->errors["status"] = "status is required";
        }
        if (empty($this->campaignId)) {
            $this->errors["campaignId"] = "campaignId is required";
        }

        // validate email format
        if (empty($this->email) == false) {
            $format = filter_var($this->email, FILTER_VALIDATE_EMAIL);
            if(empty($format)) {
                $this->errors["email"] = "email address format is invalid";
            }
        }

        if (in_array($this->status, self::STATUSES) !== true) {
            $this->errors["status"] = sprintf(
                "invalid data status, must be any of %s",
                implode(", ", self::STATUSES)
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
        return ["email", "fullName", "status", "campaignId"];
    }
}
