<?php

class PdoDb
{
	function __construct() {
		$settings = require_once("settings.php");
		$this->conn = new PDO($settings["db"]["conn"], $settings["db"]["uname"], $settings["db"]["password"]);
	}

	function getSubentities($id, $entity, $subentity) {
		return $this->conn->query("SELECT * FROM $subentity WHERE $entity"."_id=$id")->fetchAll(PDO::FETCH_ASSOC);
	}

	function getAll($entity) {
		return $this->conn->query("SELECT * FROM $entity")->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getWhere($entity, $params, $subentity=false) {
		function paramsToSqlSelect($param) {
			$key = $param[0];
			return "$key = :$key";
		}

		$paramsstr = implode(" ", array_map("paramsToSqlSelect", $params));
		$query = $this->conn->prepare("SELECT * FROM $entity WHERE $paramsstr");
	
		foreach ($params as $param) {
			$key = $param[0];
			$value = $param[1];
			$query->bindParam(":$key", $value);
		}
		$query->execute();
	
		$value = $query->fetch(PDO::FETCH_ASSOC);
		if ($value && $subentity) {
			$subentities = $this->getSubentities($value['id'], $entity, $subentity);
			$value["$subentity"."s"] = $subentities;
		}
		return $value;
	}
	
	function insert($entity, $params) {
		function paramsToSqlInsert($key) {
			return ":$key";
		}

		$keys = implode(", ", array_keys($params));
		$values = implode(", ", array_map("paramsToSqlInsert", array_keys($params)));

		$querystr = "INSERT INTO $entity ($keys) VALUES ($values)";
		$query = $this->conn->prepare($querystr);

		foreach($params as $key => &$value) {
			$query->bindParam(":$key", $value);
		}

		$res = $query->execute();
		if ($res) {
			$lastInsertId = $this->conn->lastInsertId();
			return $this->getWhere($entity, [["id", $lastInsertId]]);
		}
		return $res;
	}
	
	function deleteWhere($entity, $params) {
		function paramsToSql($param) {
			$key = $param[0];
			return "$key = :$key";
		}

		$paramsstr = implode(" ", array_map("paramsToSql", $params));
		$query = $this->conn->prepare("DELETE FROM $entity WHERE $paramsstr");
	
		foreach ($params as $param) {
			$key = $param[0];
			$value = $param[1];
			$query->bindParam(":$key", $value);
		}
		$res = $query->execute();
		return ["success" => $res];
	}
}