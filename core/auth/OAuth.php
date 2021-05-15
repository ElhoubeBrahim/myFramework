<?php


	namespace app\core\auth;

	use app\core\Application;
	use Google_Client;
	use League\OAuth2\Client\Provider\Facebook;

	/**
	 * Class OAuth
	 * This class is used to handle 3rd party authorization - OAuth 2
	 * @package app\core\auth
	 */
	class OAuth
	{

		// Get providers instances
		public $googleClient;
		public $facebookClient;
		public $githubClient;
		// ...

		/**
		 * OAuth constructor
		 * @param $config
		 */
		public function __construct($config) {
			// Setup providers clients
			$this->setup_google_client($config['google']);
			$this->setup_facebook_client($config['facebook']);
			$this->setup_github_client($config['github']);
			// ...
		}

		/**
		 * Authenticate user with returned data from oauth providers
		 * @param $data
		 * @param $field
		 * @param $value
		 */
		public function auth($data, $field, $value) {
			// Get user class
			$User = Application::$app->user;
			// If users exists
			if ($User->exists($field, $value)) {
				// Update data
				$User->update($data, [$field => $value]);
			} else {
				// Else
				// Create new user
				$User->create($data);
			}

			// Login user
			$User->new_session([
				'id' => $User->id($field, $value),
				$field => $value
			]);
		}

		/**
		 * Setup google provider
		 * @param $config
		 */
		private function setup_google_client($config) {
			// Get application url
			$url = Application::$app->url;
			// Create new instance
			$this->googleClient = new Google_Client();
			// Set up google client options
			$this->googleClient->setClientId($config['id']);
			$this->googleClient->setClientSecret($config['secret']);
			$this->googleClient->setRedirectUri($url . $config['redirect']);
			$this->googleClient->addScope('email');
			$this->googleClient->addScope('profile');
		}

		/**
		 * Setup facebook provider
		 * @param $conifg
		 */
		private function setup_facebook_client($conifg) {
			// Get needed data
			$id = $conifg['id'];
			$url = Application::$app->url . $conifg['redirect'];

			// Create new facebook client instance
			$this->facebookClient = new Facebook([
				'clientId' => $id,
				'clientSecret' => $conifg['secret'],
				'redirectUri' => $url,
				'graphApiVersion' => 'v2.10'
			]);
		}

		/**
		 * Setup github provider
		 * @param $config
		 */
		private function setup_github_client($config) {
			// Get application url
			$url = Application::$app->url . $config['redirect'];
			// Init authorize url params
			$params = [
				'client_id' => $config['id'],
				'redirect_uri' => $url,
				'scope' => 'user'
			];
			// Set github provider options
			$this->githubClient['url'] = 'https://github.com/login/oauth/authorize?' . http_build_query($params);
			$this->githubClient['access_token_url'] = 'https://github.com/login/oauth/access_token';
			$this->githubClient['api_url'] = 'https://api.github.com';
			$this->githubClient['config'] = $config;
			$this->githubClient['config']['url'] = $url;
		}

		/**
		 * You can add more providers setup methods here ...
		 */

	}