<?php


	namespace app\models\oauth;


	use app\core\Application;
	use app\core\auth\OAuth;
	use app\core\mvc\Model;
	use League\OAuth2\Client\Provider\Facebook;

	class FacebookModel extends Model
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
			// Setup facebook client
			$this->setup(OAuth::$config['facebook']);
		}

		public function setup($config) {
			// Get needed data
			$id = $config['id'];
			$url = Application::$app->url . $config['redirect'];

			// Create new facebook client instance
			$this->client = new Facebook([
				'clientId' => $id,
				'clientSecret' => $config['secret'],
				'redirectUri' => $url,
				'graphApiVersion' => 'v2.10'
			]);
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
			$token = $this->client->getAccessToken('authorization_code', [
				'code' => $this->code
			]);
			return $this->client->getResourceOwner($token)->toArray();
		}

		public function data($info) {
			return [
				'oauth_id' => $info['id'],
				'name' => $info['name'],
				'email' => $info['email'],
				'active' => 1,
				'oauth' => 1,
				'provider' => 'facebook'
			];
		}

		public function url() {
			return $this->client->getAuthorizationUrl();
		}

	}