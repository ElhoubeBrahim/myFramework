<?php

	/**
	 * This file contains all your defined routes
	 * You can split it into multiple files and include them here
	 */

	use app\core\Application;
	use app\controllers\homeController;

	// Get the router instance
	$router = Application::$app->router;

	// Home route
		// Method => GET
		// Path   => /
		// Action => homeController->home(Request, Response)
	$router->get('/', [homeController::class, 'home']);