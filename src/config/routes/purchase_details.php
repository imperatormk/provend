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

			$response->getBody()->write(json_encode(array('id' => $id)));
		} else {
			$response->getBody()->write(json_encode(array('msg' => 'not_found')));
		}

		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus($exists ? 200 : 404);
	});

	$group->delete('/{id}', function (ServerRequestInterface $request, ResponseInterface $response, array $args) {
		$id = $args['id'];
		$item = R::load('details', $id);
		$status = R::trash($item);

		$response->getBody()->write(json_encode(['success' => $status == 1 ? true : false]));
		return $response
			->withHeader('Content-Type', 'application/json');
	});
};