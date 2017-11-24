<?php
/**
 * @var $app \Slim\App
 */

use App\Controllers\UserController;

$app->get('/', function ($req, $res) {
    return "api users";
});

$app->get('/users', UserController::class . ':get');
$app->get('/users/{id}', UserController::class . ':getOne');
$app->post('/users', UserController::class . ':create');
$app->put('/users/{id}', UserController::class . ':update');
$app->delete('/users', UserController::class . ':delete');