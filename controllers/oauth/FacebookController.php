<?php


	namespace app\controllers\oauth;


	use app\core\Application;
	use app\core\mvc\Controller;

	class FacebookController extends Controller
	{

		public function auth($req, $res) {
			// Sanitize params
			$req->params = Application::sanitize($req->params);
			// Get user class
			$User = Application::$app->user;
			// Get facebook client
			$facebookClient = $User->oauth->facebookClient;
			// If user logged in using facebook
			if (!isset($req->params['code'])) {
				$res->redirect('/login');
			}

			// Auth and get data from facebook
			try {
				$token = $facebookClient->getAccessToken('authorization_code', [
					'code' => $req->params['code']
				]);
				$info = $facebookClient->getResourceOwner($token)->toArray();

				// If there is no email
				if (!isset($info['email'])) {
					throw new \Exception('Please provide email address');
				}

				// Get user data
				$data = [
					'oauth_id' => $info['id'],
					'name' => $info['name'],
					'email' => $info['email'],
					'active' => 1,
					'oauth' => 1,
					'provider' => 'facebook'
				];

				// Authenticate user
				$User->oauth->auth($data, 'email', $data['email']);
			} catch (\Exception $e) {
				$res->redirect('/login');
			}

			// Redirect to dashboard
			$res->redirect('/dashboard');
		}

	}