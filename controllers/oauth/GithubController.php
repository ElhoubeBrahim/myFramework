<?php


	namespace app\controllers\oauth;


	use app\core\Application;
	use app\core\mvc\Controller;

	class GithubController extends Controller
	{

		public function auth($req, $res) {
			// Sanitize params
			$req->params = Application::sanitize($req->params);
			// Get user class
			$User = Application::$app->user;
			// Get github client
			$githubClient = $User->oauth->githubClient;
			// If user logged in using github
			if (!isset($req->params['code'])) {
				$res->redirect('/login');
			}

			// Get access token using a post request
			$token = $req->api($githubClient['access_token_url'], [
				'client_id' => $githubClient['config']['id'],
				'client_secret' => $githubClient['config']['secret'],
				'redirect_uri' => $githubClient['config']['url'],
				'User-Agent' => $githubClient['config']['name'],
				'code' => $req->params['code']
			]);
			// Check if code is not valid
			if (!isset($token->access_token)) {
				$res->redirect('/login');
			}
			// Get user data
			$info = $req->api($githubClient['api_url'] . '/user', null, [
				"Authorization: Bearer $token->access_token",
				'User-Agent: ' . $githubClient['config']['name']
			]);

			// Get user email
			$info->email = $req->api('https://api.github.com/user/emails', null, [
				"Authorization: Bearer $token->access_token",
				'User-Agent: ' . $githubClient['config']['name']
			])[0]->email;

			// If there is no email
			if (!isset($info->email)) {
				$res->redirect('/login');
			}

			// Get user data
			$data = [
				'oauth_id' => $info->id,
				'name' => $info->name,
				'email' => $info->email,
				'active' => 1,
				'oauth' => 1,
				'provider' => 'github'
			];

			// Authenticate user
			$User->oauth->auth($data, 'email', $data['email']);
			// Redirect to dashboard
			$res->redirect('/dashboard');
		}

	}