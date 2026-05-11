<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$validator = app(App\Services\CspValidator::class);
$renderer = app(App\Services\StaticRenderer::class);
$manifest = $renderer->renderAll();
$res = $validator->validateAll($manifest);
echo json_encode(['passed' => $res['passed'], 'summary' => $res['summary']], JSON_PRETTY_PRINT);
