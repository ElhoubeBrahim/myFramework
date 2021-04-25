<?php

	/**
	 * To add a middleware you have to create a new class in this directory,
	 * then copy the content of this file and implement the run method.
	 * You can use Request and Response classes to build your middleware
	 */

	namespace app\middlewares;
	use app\core\router\Middleware;


	class myMiddleware extends Middleware
	{

		public function run($req, $res) {
			echo 'running my middleware ... <br>';
		}
	}