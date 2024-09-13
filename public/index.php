<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use GuzzleHttp\Psr7\Response;

$response = new Response(body: '<h1>Hello, World!</h1>');
echo $response->getBody()->getContents();
