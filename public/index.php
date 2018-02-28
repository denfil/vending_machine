<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';

$settings = require __DIR__ . '/../config/settings.php';
$app = new \Slim\App($settings);

require __DIR__ . '/../config/dependencies.php';
require __DIR__ . '/../config/routes.php';

$app->run();
