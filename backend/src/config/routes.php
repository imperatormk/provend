<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $group) {	
	$group->group('/vendors', require __DIR__ . '/routes/vendors.php');
	$group->group('/purchases', require __DIR__ . '/routes/purchases.php');
	$group->group('/purchase-details', require __DIR__ . '/routes/purchase_details.php');
};