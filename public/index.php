<?php

require __DIR__ . '/../vendor/autoload.php';

$database = require __DIR__ . '/../config/database.php';
$container = require __DIR__ . '/../config/container.php';

$app = require __DIR__ . '/../routes/api.php';
$app->run();