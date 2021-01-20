<?php

use \RedBeanPHP\R as R;
use App\Factory\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteCollectorProxy;

return function (RouteCollectorProxy $group) {
	$group->get('/', function (ServerRequestInterface $request, ResponseInterface $response) {
		$items = R::findAll('purchase');

		$response->getBody()->write(json_encode(array_values($items)));
		return $response
			->withHeader('Content-Type', 'application/json');
	});

	$group->get('/{id}', function (ServerRequestInterface $request, ResponseInterface $response, array $args) {
		$id = $args['id'];
		$item = R::load('purchase', $id);

		$exists = $item['id'] > 0 && $item->exists('vendor');
		if (!$exists) {
			$response->getBody()->write(json_encode(array('msg' => 'not_found')));
		}
		else {
			R::preload($item, array('ownDetail' => 'detail'));
			$item['details'] = $item['ownDetail'];
			unset($item['ownDetail']);
			$response->getBody()->write(json_encode($item));
		}

		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus($exists ? 200 : 404);
	});

	$group->post('/', function (ServerRequestInterface $request, ResponseInterface $response) {
		$parsedBody = $request->getParsedBody();

		$vendorId = $parsedBody['vendor_id'];
		$vendor = R::load('vendor', $vendorId);
		$exists = $vendor['id'] > 0;

		if ($exists) {
			$item = R::dispense('purchase');
			foreach($parsedBody as $key => $value) {
				if ($key != 'vendor_id') $item[$key] = $value;
			};
			$vendor->xownPurchaseList[] = $item;
			$id = R::store($vendor);

			$vendor = R::load('vendor', $vendorId);
			R::preload($vendor, array('ownPurchase' => 'purchase'));
			$purchase = end($vendor['ownPurchase']);

			$response->getBody()->write(json_encode(array($purchase)));
		} else {
			$response->getBody()->write(json_encode(array('msg' => 'not_found')));
		}
		
		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus($exists ? 200 : 404);
	});

	$group->delete('/{id}', function (ServerRequestInterface $request, ResponseInterface $response) {
		$id = $args['id'];
		$item = R::load('purchase', $id);
		$status = R::trash($item);

		$response->getBody()->write(json_encode(['success' => $status == 1 ? true : false]));
		return $response
			->withHeader('Content-Type', 'application/json');
	});
};