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
        $model = new static();
        $record = $model->sqlSelect(["id"=>$id]);
        $result = null;

        if(empty($record) === false) {
            $result = new static($record[0]);
        }

        return $result;
    }

    /**
     * Lists all the records for this model.
     *
     * @param Array $filters An array of key values with optional filters.
     * @return Array An array of objects of the model if any match the criteria.
     */
    public static function list(Array $filters = null)
    {
        $model = new static();
        $records = $model->sqlSelect($filters);
        $result = [];

        if(empty($records) === false) {
            foreach ($records as $record) {
                array_push($result, new static($record));
            }
        }

        return $result;
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
            $record = null;
            if ($this->isNew()) {
                $record = $this->sqlInsert($this->getAttributes(["id"]));
            } else {
                $record = $this->sqlUpdate($this->id, $this->getAttributes(["id"]));
            }

            $this->assignAttributes($record);
        }
        return count($this->errors) < 1;
    }

    /**
     * Deletes the current record.
     *
     * @return boolean Whether the record could be deleted or not
     */
    public function delete()
    {
        if ($this->isNew()) {
            return true;
        }
        return $this->sqlDelete($this->id);
    }


    /**
     * Truncates the current table data.
     *
     * @return null
     */
    public function truncate()
    {
        $conn = $this->getConnection();
        $table = $this->getTableName();

        // first set FK check to false
        $sql = "SET FOREIGN_KEY_CHECKS = 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->close();

        // now we actually truncate
        $sql = "TRUNCATE TABLE $table;";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->close();

        // re-enable FK checks
        $sql = "SET FOREIGN_KEY_CHECKS = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->close();

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

        if (array_key_exists("id", $data)) {
            $this->id = $data["id"];
        }
    }

    /**
     * Returns an array of whitelisted attributes and its values for this model.
     * The values can be filtered out with the $exclude parameter so we may only
     * pull some of them.
     *
     * @param Array $exclude Optional parameter for any excluded
     *  attributes/properties that we don't want in the list.
     * @return Array An array containing key-value pairs of attributes for
     *  this model.
     */
    protected function getAttributes(Array $exclude = [])
    {
        $attributes = [];

        foreach ($this->allowedFields() as $key) {
            if (array_key_exists($key, $exclude)) {
                continue;
            }

            $attributes[$key] = $this->{$key};
        }

        return $attributes;
    }

    /**
     * Selects records from the database with the given filters and returns them
     * as an associative array.
     *
     * @param Array $filters An optional list of filters for the select query.
     * @return Array An associative array containing the database records.
     */
    private function sqlSelect(Array $filters = null)
    {
        $conn = $this->getConnection();
        $table = $this->getTableName();
        $sql = null;
        $stmt = null;
        $records = null;
        $boundDefs = [];
        $boundChars = [];

        if ($conn) {
            if (empty($filters)) {
                $sql = "SELECT * FROM $table";
                $stmt = $conn->prepare($sql);
            } else {
                foreach ($filters as $key => $value) {
                    array_push($boundChars, "$key = ?");
                    if (is_numeric($value)) {
                        array_push($boundDefs, "i");
                    } else {
                        array_push($boundDefs, "s");
                    }
                }

                $boundDefs = implode("", $boundDefs);
                $boundChars = implode(", ", $boundChars);

                $sql = "SELECT * FROM $table WHERE $boundChars";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param($boundDefs, ...array_values($filters));
            }

            $stmt->execute();
            $res = $stmt->get_result();

            $records = $res->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            return $records;
        }

        return false;
    }

    /**
     * Inserts a record into the database with the given values and returns the
     * record as an associative array.
     *
     * @param Array $filters Values of the record to be inserted.
     * @return Array An associative array containing the inserted database
     *  record.
     */
    private function sqlInsert(Array $args)
    {
        $conn = $this->getConnection();
        $table = $this->getTableName();
        $sql = null;
        $stmt = null;
        $record = null;
        $boundDefs = [];
        $boundChars = [];

        foreach ($args as $key => $value) {
            if (is_numeric($value)) {
                array_push($boundDefs, "i");
            } else {
                array_push($boundDefs, "s");
            }

            array_push($boundChars, "?");
        }

        $boundChars = implode(", ", $boundChars);

        $cols = implode(", ", array_keys($args));

        if ($conn) {
            $sql = "INSERT INTO $table ($cols) VALUES ($boundChars);";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(implode("", $boundDefs), ...array_values($args));
            $stmt->execute();
            $insertId = $stmt->insert_id;
            $stmt->close();
            return $this->sqlSelect(["id"=>$insertId])[0];
        }

        return false;
    }

    /**
     * Updates a record into the database with the given values for the given
     * record primary key and returns the record as an associative array.
     *
     * @param int $id The primary key of the record to update.
     * @param Array $filters Values of the record to be updated.
     * @return Array An associative array containing the updated database
     *  record.
     */
    private function sqlUpdate($id, $args)
    {
        $conn = $this->getConnection();
        $table = $this->getTableName();
        $sql = null;
        $stmt = null;
        $record = null;
        $boundDefs = [];
        $boundChars = [];

        foreach ($args as $key => $value) {
            if (is_numeric($value)) {
                array_push($boundDefs, "i");
            } else {
                array_push($boundDefs, "s");
            }

            array_push($boundChars, "$key = ?");
        }

        // this is to bind the ID which is at the end
        // but outside of the column list
        $args = array_merge($args, ["id"=>$id]);
        array_push($boundDefs, "i");

        $boundChars = implode(", ", $boundChars);

        if ($conn) {
            $sql = "UPDATE $table SET $boundChars WHERE (id = ?);";
            $stmt = $conn->prepare($sql);

            $stmt->bind_param(implode("", $boundDefs), ...array_values($args));
            $stmt->execute();
            $stmt->close();

            return $this->sqlSelect(["id"=>$id])[0];
        }

        return false;
    }

    /**
     * Removes a record from the database for the given primary key
     *
     * @param int $id The primary key of the record to delete.
     * @return boolean Whether the record could be deleted or not.
     */
    private function sqlDelete($id)
    {
        $conn = $this->getConnection();
        $table = $this->getTableName();
        $sql = null;
        $stmt = null;

        if ($conn) {
            $sql = "DELETE FROM $table WHERE (id = ?);";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            return true;
        }

        return false;
    }

    /**
     * Creates a new MySql connection with the env vars defined.
     *
     * @return \mysqli A mysql connection object.
     */
    private function getConnection()
    {
        $dbHost = getenv("DB_HOST");
        $dbPort = getenv("DB_PORT");
        $dbUser = getenv("DB_USER");
        $dbPwd = getenv("DB_PWD");
        $dbName = getenv("DB_NAME");
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $conn = new \mysqli($dbHost, $dbUser, $dbPwd, $dbName, $dbPort);

        if ($conn->connect_errno) {
            $errDetail = "Could connect to MySql db '$dbName' on $dbHost:$dbPort with $dbUser user";
            throw new \Exception($errDetail);
        }

        return $conn;
    }

}
