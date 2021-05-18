<?php

	/**
	 * To add a middleware you have to create a new class in this directory,
	 * then copy the content of this file and implement the run method.
	 * You can use Request and Response classes to build your middleware
	 */

	namespace app\middlewares;

	use app\core\router\Middleware;
	use app\core\Application;


	class authMiddleware extends Middleware
	{

		public function run($req, $res)
		{
			$Auth = Application::$app->auth;

			if ($Auth->auth()) {
				$res->redirect('/profile');
			}
		}
	}