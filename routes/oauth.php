<?php

	use app\core\Application;
	use app\controllers\oauth\GoogleController;
	use app\controllers\oauth\FacebookController;
	use app\controllers\oauth\GithubController;


	// Get the router instance
	$router = Application::$app->router;

	$router->get('/oauth/google', [GoogleController::class, 'auth']);
	$router->get('/oauth/facebook', [FacebookController::class, 'auth']);
	$router->get('/oauth/github', [GithubController::class, 'auth']);