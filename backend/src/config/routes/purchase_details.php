<?php

use \RedBeanPHP\R as R;
use App\Factory\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteCollectorProxy;

return function (RouteCollectorProxy $group) {
	$group->post('/', function (ServerRequestInterface $request, ResponseInterface $response) {
		$parsedBody = $request->getParsedBody();

		$purchaseId = $parsedBody['purchase_id'];
		$purchase = R::load('purchase', $purchaseId);
		$exists = $purchase['id'] > 0;

		if ($exists) {
			$item = R::dispense('detail');
			foreach($parsedBody as $key => $value) {
				if ($key != 'purchase_id') $item[$key] = $value;
			}
			$purchase->xownDetailList[] = $item;
			$id = R::store($purchase);

			$purchase = R::load('purchase', $purchaseId);
			R::preload($purchase, array('ownDetail' => 'detail'));
			$detail = end($purchase['ownDetail']);

			$response->getBody()->write(json_encode(array($detail)));
		} else {
			$response->getBody()->write(json_encode(array('msg' => 'not_found')));
		}

		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus($exists ? 200 : 404);
	});
};