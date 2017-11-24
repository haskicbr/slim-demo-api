<?php

use App\Models\Database;

require __DIR__ . '/../vendor/autoload.php';

$config = require "config/main.php";
new Database($config['db']);
$app = new \Slim\App($config['app']);

$container = $app->getContainer();

$container['validator'] = function () {
    return new Awurth\SlimValidation\Validator();
};

require __DIR__ . '/routes/main.php';