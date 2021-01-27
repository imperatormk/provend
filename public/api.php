<?php

function prepareQueryParams($querystr) {
	$queries = [];
	parse_str($querystr, $queries);
	return $queries;
}

function receiveJsonRequest() {
	$jsonstr = file_get_contents("php://input");
	$data = json_decode($jsonstr, true);
	return $data;
}

function returnJsonResponse($data, $statusCode) {
	header("Content-Type: application/json");
	http_response_code($statusCode);
	return json_encode($data);
}

function getEntityName() {
	$routeName = basename($_SERVER["PHP_SELF"], ".php");
	$map = [
		"vendors" => "vendor",
		"purchases" => "purchase",
		"details" => "detail"
	];
	return isset($map[$routeName]) ? $map[$routeName] : false;
}

function executeRequest() {
	try {
		require_once("db.php");
		$pdoDb = new PdoDb();
		
		$entityName = getEntityName();
		$statusCode = 200;

		if ($entityName) {
			if ($_SERVER["REQUEST_METHOD"] == "GET") {
				$query = prepareQueryParams($_SERVER["QUERY_STRING"]);
				$id = isset($query["id"]) ? $query["id"] : false;
				
				if ($id) {
					$subentity = isset($query["subentity"]) ? $query["subentity"] : false;
					$res = $pdoDb->getWhere($entityName, [["id", $id]], $subentity);
				} else {
					$res = $pdoDb->getAll($entityName);
				}
			} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$req = receiveJsonRequest();
				$res = $pdoDb->insert($entityName, $req);
			} else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
				$query = prepareQueryParams($_SERVER["QUERY_STRING"]);
				$id = isset($query["id"]) ? $query["id"] : false;
			
				if ($id) {
					$res = $pdoDb->deleteWhere($entityName, [["id", $id]]);
				} else {
					$res = false;
				}
			}
			if ($res == false) {
				$res = ["msg" => "not_found"];
				$statusCode = 404;	
			}
		} else {
			$res = ["msg" => "invalid_route"];
			$statusCode = 400;
		}
	} catch (Exception $e) {
		$res = ["msg" => "error"];
		$statusCode = 500;
	}
	return returnJsonResponse($res, $statusCode);
}