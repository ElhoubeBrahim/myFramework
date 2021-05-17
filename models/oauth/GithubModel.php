<?php


	namespace app\models\oauth;


	use app\core\Application;
	use app\core\auth\OAuth;
	use app\core\mvc\Model;

	class GithubModel extends Model
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
			// Setup github client
			$this->setup(OAuth::$config['github']);
		}

		public function setup($config) {
			// Get application url
			$url = Application::$app->url . $config['redirect'];
			// Init authorize url params
			$params = [
				'client_id' => $config['id'],
				'redirect_uri' => $url,
				'scope' => 'user'
			];
			// Set github provider options
			$this->client['url'] = 'https://github.com/login/oauth/authorize?' . http_build_query($params);
			$this->client['access_token_url'] = 'https://github.com/login/oauth/access_token';
			$this->client['api_url'] = 'https://api.github.com';
			$this->client['config'] = $config;
			$this->client['config']['url'] = $url;
		}

		public function auth() {
			// Get request instance
			$req = Application::$app->request;

			// Get user info
			$info = $this->info($req);

			// If there is no email
			if (!isset($info->email)) {
				throw new \Exception('email');
			}

			// Get user data
			$data = $this->data($info);

			// Authenticate user
			$this->User->oauth->auth($data, 'email', $data['email']);
		}

		public function info($req) {
			// Get access token using a post request
			$token = $req->api($this->client['access_token_url'], [
				'client_id' => $this->client['config']['id'],
				'client_secret' => $this->client['config']['secret'],
				'redirect_uri' => $this->client['config']['url'],
				'User-Agent' => $this->client['config']['name'],
				'code' => $this->code
			]);
			// Check if code is not valid
			if (!isset($token->access_token)) {
				throw new \Exception('token');
			}
			// Get user data
			$info = $req->api($this->client['api_url'] . '/user', null, [
				"Authorization: Bearer $token->access_token",
				'User-Agent: ' . $this->client['config']['name']
			]);

			// Get user email
			$info->email = $req->api('https://api.github.com/user/emails', null, [
				"Authorization: Bearer $token->access_token",
				'User-Agent: ' . $this->client['config']['name']
			])[0]->email;

			return $info;
		}

		public function data($info) {
			return [
				'oauth_id' => $info->id,
				'name' => $info->name,
				'email' => $info->email,
				'active' => 1,
				'oauth' => 1,
				'provider' => 'github'
			];
		}

		public function url() {
			return $this->client['url'];
		}

	}