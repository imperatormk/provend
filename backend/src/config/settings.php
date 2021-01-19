<?php

error_reporting(0);
ini_set('display_errors', '0');

date_default_timezone_set('Europe/Skopje');

$settings = [];

$settings['root'] = dirname(__DIR__);
$settings['public'] = $settings['root'] . '/public';

$settings['error'] = [
	'display_error_details' => true,
	'log_errors' => true,
	'log_error_details' => true,
];

$settings['logger'] = [
	'name' => 'app',
];

return $settings;