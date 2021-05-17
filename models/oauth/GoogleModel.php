<?php


	namespace app\models\oauth;


	use app\core\Application;
	use app\core\auth\OAuth;
	use app\core\mvc\Model;
	use Google_Client;
	use Google_Service_Oauth2;

	class GoogleModel extends Model
	{

		private $User;
		public $client;

		public $code;

		public function __construct($data) {
			// Run parents constructors
			parent::__construct();
			// Get user
			$this->User = Application::$app->user;
			// Load model data
			$this->load($data);
			// Setup google client
			$this->setup(OAuth::$config['google']);
		}

		public function setup($config) {
			// Get application url
			$url = Application::$app->url;
			// Create new instance
			$this->client = new Google_Client();
			// Set up google client options
			$this->client->setClientId($config['id']);
			$this->client->setClientSecret($config['secret']);
			$this->client->setRedirectUri($url . $config['redirect']);
			$this->client->addScope('email');
			$this->client->addScope('profile');
		}

		public function auth() {
			// Get user info
			$info = $this->info();

			// If there is no email
			if (!isset($info['email'])) {
				throw new \Exception('email');
			}

			// Get user data
			$data = $this->data($info);

			// Authenticate user
			$this->User->oauth->auth($data, 'email', $data['email']);
		}

		public function info() {
			// Get access token
			$token = $this->client->fetchAccessTokenWithAuthCode($this->code);
			if (!isset($token['access_token'])) {
				throw new \Exception('token');
			}
			$this->client->setAccessToken($token['access_token']);

			// get profile info
			$google_oauth = new Google_Service_Oauth2($this->client);
			 return $google_oauth->userinfo->get();
		}

		public function data($info) {
			return [
				'oauth_id' => $info['id'],
				'name' => $info['name'],
				'email' => $info['email'],
				'active' => 1,
				'oauth' => 1,
				'provider' => 'google'
			];
		}

		public function url() {
			return $this->client->createAuthUrl();
		}

	}