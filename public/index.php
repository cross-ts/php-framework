<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use App\App;
use Http\Middleware\Processor;
use GuzzleHttp\Psr7\ServerRequest;

$app = new App();

$request = ServerRequest::fromGlobals();
$processor = new Processor(
    handler: $app,
    middlewares: new SplQueue(),
);
$response = $processor->handle($request);

echo $response->getBody();
