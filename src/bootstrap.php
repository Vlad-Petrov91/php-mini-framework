<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Utils;
use HttpSoft\Emitter\SapiEmitter;

require dirname(__DIR__) . "/vendor/autoload.php";

$request = ServerRequest::fromGlobals();

$page = $request->getQueryParams()['page'] ?? 'home';

ob_start();

include dirname(__DIR__) . "/{$page}.php";

$content = ob_get_clean();

$stream = Utils::streamFor($content);

$responce = new Response();
$responce = $responce->withStatus(418)->withHeader("X-Powered-By", "PHP")->withBody($stream);

// http_response_code($responce->getStatusCode());
$emitter = new SapiEmitter();
$emitter->emit($responce);

echo $responce->getBody();
