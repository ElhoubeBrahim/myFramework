<?php


	namespace app\middlewares;


	use app\core\Application;
	use app\core\router\Middleware;

	class rememberUserMiddleware extends Middleware
	{

		public function run($req, $res) {
			// Get user id and remember token
			$id = $_COOKIE['id'] ?? null;
			$token = $_COOKIE['token'] ?? null;

			// Get user class
			$User = Application::$app->user;

			// If user exists and token is valid
			if (
				$User->exists('id', $id) &&
				$User->token->verify($id, $token, 'r')
			) {
				$User->new_session([
					'id' => $id,
					'email' => $User->get_info('id', $id)['email']
				]);
			}
		}

	}