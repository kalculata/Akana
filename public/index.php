<?php
	require_once __DIR__.'/../akana/Kernel.php';

	use Akana\Request;

	if(isset($_GET["uri"]) && !empty($_GET["uri"])) {
		$uri = $_GET["uri"];
	} else {
		$uri = $_SERVER['REQUEST_URI'];
	}

	$http_verb = strtolower($_SERVER['REQUEST_METHOD']);
	$request = Request::get_request_body();
	$app = new Kernel($request, $http_verb, $uri);
	$app->start();