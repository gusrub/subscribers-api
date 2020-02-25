<?php

namespace SubscribersApi;

require __DIR__ . '/../vendor/autoload.php';

# load dotenv
$env = getenv('SUBSCRIBERS_API_ENV');
$dotenv = \Dotenv\Dotenv::createMutable(__DIR__ . "/../", ".env.$env");
$dotenv->load();

use SubscribersApi\Models\Subscriber;
use SubscribersApi\Models\Field;

// Load up the route manager that allows us to define endpoints and what should
// happen when each of them is accessed
$routes = new RouteManager();

// Root GET route, just some dummy response
$routes->get('/', function($routeInfo){
    Response::render(["message"=>"this is an API"], 200);
});

/**
 * SUBSCRIBER RESOURCES
 */

// Subscribers resource index [GET]
$routes->get('subscribers', function($routeInfo){
    $subscribers = Subscriber::list();
    Response::render($subscribers, 200);
});

// Subscribers resource retrieval [GET]
$routes->get('subscribers/:id', function($routeInfo){
    $subscriber = Subscriber::load($routeInfo["id"]);
    if (empty($subscriber)) {
        Response::render(Response::NOT_FOUND, 404);
    } else {
        Response::render($subscriber, 200);
    }
});

// // Subscriber resource creation [POST]
$routes->post('subscribers', function($routeInfo, $data){
    $subscriber = new Subscriber($data);
    if ($subscriber->save()) {
        Response::render($subscriber, 201);
    } else {
        Response::render($subscriber->getErrors(), 400);
    }
});

// Subscriber resource update [PUT]
$routes->put('subscribers/:id', function($routeInfo, $data){
    $subscriber = Subscriber::load($routeInfo["id"]);
    if(empty($subscriber)) {
        Response::render(Response::NOT_FOUND, 404);
    } else if ($subscriber->save($data)) {
        Response::render($subscriber, 200);
    } else {
        Response::render($subscriber->getErrors(), 400);
    }
});

// Subscriber resource removal [DELETE]
$routes->delete('subscribers/:id', function($routeInfo){
    $subscriber = Subscriber::load($routeInfo["id"]);
    if (empty($subscriber)) {
        Response::render(Response::NOT_FOUND, 404);
    } else if ($subscriber->delete()) {
        Response::render(null, 204, false);
    } else {
        Response::render($subscriber->getErrors(), 400);
    }
});

/**
 * FIELDS RESOURCES
 */

// Fields resource index [GET]
$routes->get('subscribers/:subscriberId/fields', function($routeInfo){
    $subscriberId = $routeInfo["subscriberId"];
    $fields = Field::list(["subscriberId"=>$subscriberId]);
    Response::render($fields, 200);
});

// Fields resource retrieval [GET]
$routes->get('subscribers/:subscriberId/fields/:id', function($routeInfo){
    $field = Field::load($routeInfo["id"]);
    if (empty($field)) {
        Response::render(Response::NOT_FOUND, 404);
    } else {
        Response::render($field, 200);
    }
});

// Fields resource creation [POST]
$routes->post('subscribers/:subscriberId/fields', function($routeInfo, $data){
    $field = new Field($data);
    if ($field->save()) {
        Response::render($field, 201);
    } else {
        Response::render($field->getErrors(), 400);
    }
});

// Fields resource update [PUT]
$routes->put('subscribers/:subscriberId/fields/:id', function($routeInfo, $data){
    $field = Field::load($routeInfo["id"]);
    if(empty($field)) {
        Response::render(Response::NOT_FOUND, 404);
    }
    else if ($field->save($data)) {
        Response::render($field, 200);
    } else {
        Response::render($field->getErrors(), 400);
    }
});

// Subscriber resource removal [PUT]
$routes->delete('subscribers/:subscriberId/fields/:id', function($routeInfo){
    $field = Field::load($routeInfo["id"]);
    if (empty($field)) {
        Response::render(Response::NOT_FOUND, 404);
    } else if ($field->delete()) {
        Response::render(null, 204, false);
    } else {
        Response::render($field->getErrors(), 400);
    }
});

// Nothing was found with given resource identifiers or no route matched
Response::render(Response::NOT_FOUND, 404);
