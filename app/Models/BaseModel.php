<?php

namespace SubscribersApi\Models;

/**
 * Contains generic structure for a data model that is shared across all others.
 */
abstract class BaseModel
{
    /**
     * @var Boolean Whether this record is persisted or not.
     */
    protected $isNew = true;

    /**
     * @var Array A list of validation errors that this record has.
     */
    protected $errors = [];

    /**
     * @var int The internal ID of this record.
     */
    public $id;

    /**
     * Gets a single record from the database for this model by the given ID.
     *
     * @param int $id The ID of the record to retrieve.
     * @return \BaseModel A record representing this model.
     */
    public static function load($id)
    {
        return new static([
            "id"=>$id
        ]);
    }

    /**
     * Lists all the records for this model.
     *
     * @return Array An array of objects of the model if any match the criteria.
     */
    public static function list()
    {
        return [
            new static(),
            new static()
        ];
    }

    /**
     * Creates a new instance for the model with the given arguments. Please
     * note that not all properties are going to be assigned, only those which
     * are public, for security reasons.
     *
     * @param Array $args An array with key-value pairs for the properties to
     * initialize this object with.
     * @return BaseModel A new instance of this object.
     */
    public function __construct(Array $args = null)
    {
        if($args !== null) {
            $this->assignAttributes($args);
        }
    }

    /**
     * Gets the validation error messages for this model.
     *
     * @return Array The validation error messages.
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Saves the current model instance to the database. If the record is new
     * then it will be created, otherwise updated. This method will run all the
     * model validations before save.
     *
     * If no arguments are given to the method then it will be saved with the
     * instance data. Otherwise the passed arguments will override the instance
     * values.
     *
     * @param Array $args An array of key-value pairs representing the
     * properties to set when updating.
     * @return boolean Whether model could be saved or not.
     */
    public function save(Array $args = null)
    {
        if($args !== null) {
            $this->assignAttributes($args);
        }

        if($this->valid()) {
            if ($this->isNew()) {

            } else {

            }
        }
        return count($this->errors) < 1;
    }

    public function delete()
    {
        return true;
    }

    /**
     * Method that must be implemented in concrete classes to validate their
     * data.
     *
     * @return boolean Whether the model data is valid or not.
     */
    abstract protected function valid();

    /**
     * Method that must be implemented in concrete classes to retrieve a list of
     * allowed fields that the user can set.
     *
     * @return Array A list of fields that the user may set.
     */
    abstract protected function allowedFields();

    /**
     * Gets information on the persisted status of this record.
     *
     * @return boolean Whether this record is new or not.
     */
    protected function isNew()
    {
        return empty($this->id);
    }

    /**
     * Assigns the received input from user arguments to the object properties.
     * Only those properties which are public can be set, anything else will
     * cause a \ModelException.
     *
     * @param Array $args An array with key-value pairs of the properties to be
     * set
     * @return null
     */
    protected function assignAttributes($data)
    {
        // filter out fields that we don't want/care for security reasons
        $filteredData = array_intersect_key($data, array_flip($this->allowedFields()));

        foreach ($filteredData as $key => $value) {
            $this->{$key} = $value;
        }
    }

}
