<?php

	use app\core\Application;
	use app\controllers\auth\RegisterController;
	use app\controllers\auth\LoginController;
	use app\controllers\auth\PasswordController;
	use app\controllers\auth\EmailController;

	use app\middlewares\guestMiddleware as guest;
	use app\middlewares\authMiddleware as auth;
	use app\middlewares\rememberUserMiddleware as remember;

	// Get the router instance
	$router = Application::$app->router;

	$router->get('/register', [RegisterController::class, 'render'])
		->middlewares(auth::class);
	$router->post('/register', [RegisterController::class, 'register'])
		->middlewares(auth::class);

	$router->get('/login', [LoginController::class, 'render'])
		->middlewares([remember::class, auth::class]);
	$router->post('/login', [LoginController::class, 'login'])
		->middlewares(auth::class);

	$router->get('/password/forgot', [PasswordController::class, 'render_forgot'])
		->middlewares(auth::class);
	$router->post('/password/forgot', [PasswordController::class, 'forgot'])
		->middlewares(auth::class);

	$router->get('/password/reset/:id/:token', [PasswordController::class, 'render_reset'])
		->middlewares(auth::class);
	$router->post('/password/reset/:id/:token', [PasswordController::class, 'reset'])
		->middlewares(auth::class);

	$router->get('/verify/email', [EmailController::class, 'render'])
		->middlewares(auth::class);
	$router->get('/verify/email/:id/:token', [EmailController::class, 'verify'])
		->middlewares(auth::class);

	$router->get('/logout', [LoginController::class, 'logout']);
