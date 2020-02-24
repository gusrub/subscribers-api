<?php

namespace SubscribersApi;

/**
 * Manages HTTTP responses for the API
 */
class Response
{
    /**
     * Defines the default content-type to be set in the response headers if
     * none is given.
     */
    const DEFAULT_CONTENT_TYPE = "Content-type: application/json; charset=utf-8";

    /**
     * Defines a default message for not found resources.
     */
    const NOT_FOUND = ["error" => "resource not found"];

    /**
     * Renders the output of the API with the given data body and status.
     *
     * @param mixed $data The data to return as response body. Will be parsed
     *  as application/json by default.
     * @param int $status The HTTTP status code to return. Defaults to 200.
     * @param String $contentType The content-type header to be returned. Will
     *  default to application/json.
     */
    public static function render($data, $status = 200, $contentType = null)
    {
        $contentType = $contentType ?? self::DEFAULT_CONTENT_TYPE;

        // if we are not returning content there is no case to encode anything
        if($contentType) {
            header($contentType);
            $responseBody =  json_encode($data);

            // Don't return anything if the data was not parsable
            if (empty(json_last_error())) {
                echo $responseBody;
            }
        }

        http_response_code($status);

        // finish the request if we wanted to render the response
        exit;
    }
}
