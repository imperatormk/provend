<?php

use \RedBeanPHP\R as R;
use App\Factory\LoggerFactory;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;

require __DIR__ . '/../helpers/Preloader.php';

return [
	'settings' => function () {
		return require __DIR__ . '/settings.php';
	},

	App::class => function (ContainerInterface $container) {
		R::setup('mysql:host=localhost;dbname=indinf', 'darko', 'pece123!');
		AppFactory::setContainer($container);
		return AppFactory::create();
	},

	ErrorMiddleware::class => function (ContainerInterface $container) {
		$app = $container->get(App::class);
		$settings = $container->get('settings')['error'];

		return new ErrorMiddleware(
			$app->getCallableResolver(),
			$app->getResponseFactory(),
			(bool)$settings['display_error_details'],
			(bool)$settings['log_errors'],
			(bool)$settings['log_error_details']
		);
	},
	
	LoggerFactory::class => function (ContainerInterface $container) {
		return new LoggerFactory($container->get('settings')['logger']);
	},
];