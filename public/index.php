<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/RouteManager.php';

// Load up the route manager that allows us to define endpoints and what should
// happen when each of them is accessed
$routes = new SubscribersApi\RouteManager();

// Root GET route, just some dummy response
$routes->get('/', function(){
    echo json_encode(["message"=>"this is an API"]);
});

/**
 * SUBSCRIBER RESOURCES
 */

// Subscribers resource index [GET]
$routes->get('subscribers', function(){
    echo json_encode(["Route matches" => "GET /subscribers"]);
});

// Subscribers resource retrieval [GET]
$routes->get('subscribers/:id', function(){
    echo json_encode(["Route matches" => "GET /subscribers/:id"]);
});

// Subscriber resource creation [POST]
$routes->post('subscribers', function(){
    echo json_encode(["Route matches" => "POST /subscribers"]);
});

// Subscriber resource update [PUT]
$routes->put('subscribers/:id', function(){
    echo json_encode(["Route matches" => "PUT /subscribers/:id"]);
});

// Subscriber resource removal [PUT]
$routes->delete('subscribers/:id', function(){
    echo json_encode(["Route matches" => "DELETE /subscribers/:id"]);
});

/**
 * FIELDS RESOURCES
 */

// Fields resource index [GET]
$routes->get('/subscribers/:id/fields', function(){
    echo json_encode(["Route matches" => "GET /subscribers/:id/fields"]);
});

// Fields resource retrieval [GET]
$routes->get('subscribers/:id/fields/:field_id', function(){
    echo json_encode(["Route matches" => "GET /subscribers/:id/fields/:field_id"]);
});

// Fields resource creation [POST]
$routes->post('subscribers/:id/fields', function(){
    echo json_encode(["Route matches" => "POST /subscribers/:id/fields"]);
});

// Fields resource update [PUT]
$routes->put('subscribers/:id/fields/:field_id', function(){
    echo json_encode(["Route matches" => "PUT /subscribers/:id/fields/:field_id"]);
});

// Subscriber resource removal [PUT]
$routes->delete('subscribers/:id/fields/:field_id', function(){
    echo json_encode(["Route matches" => "DELETE /subscribers/:id/fields/:field_id"]);
});

// Nothing was found with given resource identifiers or no route matched
http_response_code(404);
exit;
