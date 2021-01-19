<?php

use \RedBeanPHP\R as R;
use App\Factory\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteCollectorProxy;

return function (RouteCollectorProxy $group) {
	$group->get('/', function (ServerRequestInterface $request, ResponseInterface $response) {
		$items = R::findAll('vendor');

		$response->getBody()->write(json_encode(array_values($items)));
		return $response
			->withHeader('Content-Type', 'application/json');
	});

	$group->get('/{id}', function (ServerRequestInterface $request, ResponseInterface $response, array $args) {
		$id = $args['id'];
		$item = R::load('vendor', $id);

		$exists = $item['id'] > 0;
		if (!$exists) {
			$response->getBody()->write(json_encode(array('msg' => 'not_found')));
		}
		else {
			R::preload($item, array('ownPurchase' => 'purchase'));
			$item['purchases'] = $item['ownPurchase'];
			unset($item['ownPurchase']);
			$response->getBody()->write(json_encode($item));
		}

		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus($exists ? 200 : 404);
	});

	$group->post('/', function (ServerRequestInterface $request, ResponseInterface $response) {
		$parsedBody = $request->getParsedBody();

		// $loggerFactory = $this->get(\App\Factory\LoggerFactory::class);
		// $logger = $loggerFactory->addConsoleHandler()->createInstance();
		// $logger->debug('hehe');

		$item = R::dispense('vendor');
		foreach($parsedBody as $key => $value) $item[$key] = $value;
		$id = R::store($item);
		$item = R::load('vendor', $id);

		$response->getBody()->write(json_encode($item));
		return $response
			->withHeader('Content-Type', 'application/json');
	});

	$group->delete('/{id}', function (ServerRequestInterface $request, ResponseInterface $response, array $args) {
		$id = $args['id'];
		$item = R::load('vendor', $id);
		$status = R::trash($item);

		$response->getBody()->write(json_encode(['success' => $status == 1 ? true : false]));
		return $response
			->withHeader('Content-Type', 'application/json');
	});
};