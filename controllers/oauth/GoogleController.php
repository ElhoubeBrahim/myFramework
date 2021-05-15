<?php


	namespace app\controllers\oauth;


	use app\core\Application;
	use app\core\mvc\Controller;
	use Google_Service_Oauth2;

	class GoogleController extends Controller
	{

		public function auth($req, $res) {
			// Sanitize params
			$req->params = Application::sanitize($req->params);
			// Get user class
			$User = Application::$app->user;
			// Get google client
			$googleClient = $User->oauth->googleClient;
			// If user logged in using google
			if (!isset($req->params['code'])) {
				$res->redirect('/login');
			}

			// Get access token
			$token = $googleClient->fetchAccessTokenWithAuthCode($req->params['code']);
			if (!isset($token['access_token'])) {
				$res->redirect('/login');
			}
			$googleClient->setAccessToken($token['access_token']);

			// get profile info
			$google_oauth = new Google_Service_Oauth2($googleClient);
			$info = $google_oauth->userinfo->get();

			// If there is no email
			if (!isset($info['email'])) {
				$res->redirect('/login');
			}

			// Get user data
			$data = [
				'oauth_id' => $info['id'],
				'name' => $info['name'],
				'email' => $info['email'],
				'active' => 1,
				'oauth' => 1,
				'provider' => 'google'
			];

			// Authenticate user
			$User->oauth->auth($data, 'email', $data['email']);
			// Redirect to dashboard
			$res->redirect('/dashboard');
		}

	}