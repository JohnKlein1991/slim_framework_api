<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->group('/users', function (RouteCollectorProxy $group){

    $group->get('/list', function (Request $request, Response $response, $args) {
        $apiController = new ApiController();
        $result = $apiController->getUsersList($response);
        return $result;
    });

    $group->get('/get-user/{id}', function (Request $request, Response $response, $args) {
        $apiController = new ApiController();
        $id = $request->getAttribute('id') ?? null;
        $result = $apiController->getUserById($response, $id);
        return $result;
    });

    $group->delete('/remove/{id}', function (Request $request, Response $response, $args) {
        $apiController = new ApiController();
        $id = $request->getAttribute('id') ?? null;
        $result = $apiController->removeUser($response, $id);
        return $result;
    });

    $group->post('/create', function (Request $request, Response $response, $args) {
        $apiController = new ApiController();
        $data = $request->getQueryParams() ?? [];
        $result = $apiController->createUser($response, $data);
        return $result;
    });

    $group->post('/update', function (Request $request, Response $response, $args) {
        $apiController = new ApiController();
        $data = $request->getQueryParams() ?? [];
        $result = $apiController->updateUser($response, $data);
        return $result;
    });
});
