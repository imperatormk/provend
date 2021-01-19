<?php

use Middlewares\TrailingSlash;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return function (App $app) {
	$app->addBodyParsingMiddleware();
	$app->addRoutingMiddleware();

	$app->add(new Middlewares\JsonPayload());
	// $app->add(new TrailingSlash());
	$app->add(ErrorMiddleware::class);
};