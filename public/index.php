<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/di.php';

session_start();

$app = new \Slim\App(container());

require __DIR__ . '/../src/Docler/Application/Routes/routes.php';

$app->run();
