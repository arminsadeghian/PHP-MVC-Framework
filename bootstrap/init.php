<?php

const BASE_PATH = __DIR__ . '/../';

require_once BASE_PATH . 'vendor/autoload.php';

require_once BASE_PATH . "vendor/larapack/dd/src/helper.php";

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$request = new \App\Core\Http\Request();

include BASE_PATH . 'routes/web.php';

require_once BASE_PATH . 'helpers/helpers.php';

$router = new \App\Core\Routing\Router();
$router->run();

