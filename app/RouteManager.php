<?php

namespace SubscribersApi;

/**
* Allows routes to be defined for the application for a given HTTP method.
*/
class RouteManager
{
    /**
     * Defines the default content-type to be set in the response headers if
     * none is given.
     */
    const DEFAULT_CONTENT_TYPE = "Content-type: application/json; charset=utf-8";

    /**
     * Defines a GET route and a callback to be executed when said route is
     * accesed. Route must be relative. Identifiers may be used for each
     * resource in a colon fashion eg:
     *
     * resource/:id/child_resource/:child_id
     *
     * @param String $route The route path to define.
     * @param callable $callback Callable code that will be executed when route
     *  is accessed.
     * @return null
     */
    public function get($route, callable $callback)
    {
        if ($this->routeMatches($route, __FUNCTION__)) {
            http_response_code(200);
            header(self::DEFAULT_CONTENT_TYPE);
            call_user_func($callback);
            exit;
        }
    }

    /**
     * Defines a POST route and a callback to be executed when said route is
     * accesed. Route must be relative. Identifiers may be used for each
     * resource in a colon fashion eg:
     *
     * resource/:id/child_resource/:child_id
     *
     * @param String $route The route path to define.
     * @param callable $callback Callable code that will be executed when route
     *  is accessed.
     * @return null
     */
    public function post($route, callable $callback)
    {
        if ($this->routeMatches($route, __FUNCTION__)) {
            http_response_code(201);
            header(self::DEFAULT_CONTENT_TYPE);
            call_user_func($callback);
            exit;
        }
    }

    /**
     * Defines a PUT route and a callback to be executed when said route is
     * accesed. Route must be relative. Identifiers may be used for each
     * resource in a colon fashion eg:
     *
     * resource/:id/child_resource/:child_id
     *
     * @param String $route The route path to define.
     * @param callable $callback Callable code that will be executed when route
     *  is accessed.
     * @return null
     */
    public function put($route, callable $callback)
    {
        if ($this->routeMatches($route, __FUNCTION__)) {
            http_response_code(200);
            header(self::DEFAULT_CONTENT_TYPE);
            call_user_func($callback);
            exit;
        }
    }

    /**
     * Defines a DELETE route and a callback to be executed when said route is
     * accesed. Route must be relative. Identifiers may be used for each
     * resource in a colon fashion eg:
     *
     * resource/:id/child_resource/:child_id
     *
     * @param String $route The route path to define.
     * @param callable $callback Callable code that will be executed when route
     *  is accessed.
     * @return null
     */
    public function delete($route, callable $callback)
    {
        if ($this->routeMatches($route, __FUNCTION__)) {
            http_response_code(204);
            header(self::DEFAULT_CONTENT_TYPE);
            call_user_func($callback);
            exit;
        }
    }

    /**
     * Internal method to be used to check whether a given string matches the
     * pattern for a predefined route. HTTP method/verb must also match as 
     * there's the case for similar paths but different actions eg:
     * 
     * GET resource/
     * POST resource/
     *
     * @param String $route The route string to check.
     * @param String $verb The HTTP method to check.
     * @return boolean Whether the route matched or not.
     */
    private function routeMatches($route, $verb)
    {
        // route and HTTP verb must match
        $reqMethod = $_SERVER['REQUEST_METHOD'];
        if (strtolower($reqMethod) !== $verb) {
            return false;
        }

        // if request is on root the PATH_INFO won't be available
        if (array_key_exists('PATH_INFO', $_SERVER)) {
            // remove trailing slashes
            $requestRoute = ltrim(rtrim($_SERVER['PATH_INFO'], "/"), "/");
            $definedRoute = ltrim(rtrim($route, "/"), "/");

            // split parts
            $requestRoute = explode("/", $requestRoute);
            $definedRoute = explode("/", $definedRoute);

            // parts count must match, otherwise is not this route
            if (count($requestRoute) !== count($definedRoute)) {
                return false;
            }

            // we just check for resources parts, those that start with a colon like
            // :id we know are resource identifiers so we don't care if they match
            for ($i=0; $i < count($definedRoute); $i++) {
                if(substr($definedRoute[$i], 0, 1) !== ":") {
                    if ($definedRoute[$i] !== $requestRoute[$i]) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Constructs an array that maps resources and their identifiers.
     * 
     * @param String $route The route that these mappings should be build from.
     * @return Array An array containing mappings of resources and their ID's.
     */
    private function routeInfo($route)
    {
        $route = explode("/", $route);
        $resources = [];
        $ids = [];
        foreach ($route as $key => $value) {
            if($key % 2 == 0) {
                array_push($resources, $value);
            } else {
                array_push($ids, $value);
            }
        }

        if (count($ids) < count($resources)) {
            array_push($ids, null);
        }

        return array_combine($resources, $ids);
    }
}
