<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/ai-predict', 'GET');
$response = $kernel->handle($request);
echo $response->getContent();
$kernel->terminate($request, $response);
