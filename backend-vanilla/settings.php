<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

date_default_timezone_set("Europe/Skopje");

$settings = [];

$settings["root"] = dirname(__DIR__);
$settings["db"] = [
	"conn" => "mysql:host=localhost;dbname=provend",
	"uname" => "darko",
	"password" => "pece123!",
];

return $settings;