<?php

namespace App\Models;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    function __construct($config) {
        $capsule = new Capsule;
        $capsule->addConnection($config);
        $capsule->bootEloquent();
    }
}