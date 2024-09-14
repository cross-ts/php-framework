<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use App\App;
use GuzzleHttp\Psr7\ServerRequest;

$app = new App();

$request = ServerRequest::fromGlobals();
$response = $app->handle($request);

echo $response->getBody();
