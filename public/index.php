<?php

	/**
	 * This file is the main file, it runs the application on
	 * any incoming request and send the appropriate result
	 * according to the written logic
	 */

	use app\core\Application;

	// Include the composer autoload
	require('../vendor/autoload.php');
	// Get the application config
	$config = require('../config/config.php');

	// Create new Application instance
	$app = new Application(dirname(__dir__), $config);

	// Get defined routes
	require('../routes/routes.php');

	echo '<pre>';
	print_r($_SESSION);
	echo '</pre>';

	// Run the application
	$app->run();
