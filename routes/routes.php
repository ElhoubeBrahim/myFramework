<?php

	/**
	 * This file contains all your defined routes
	 * You can split it into multiple files and include them here
	 */

	use app\core\Application;
	use app\controllers\homeController;

	use app\middlewares\guestMiddleware as guest;

	// Get the router instance
	$router = Application::$app->router;

	// Home route
		// Method => GET
		// Path   => /
		// Action => homeController->home(Request, Response)
	$router->get('/', [homeController::class, 'home']);

	$router->get('/dashboard', [homeController::class, 'dashboard'])
		->middlewares(guest::class);

	$router->get('/email', [homeController::class, 'email']);

	require 'auth.php';
	require 'oauth.php';