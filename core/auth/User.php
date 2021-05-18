<?php


	namespace app\core\auth;
	use app\core\Application;


	/**
	 * Class User
	 * @package app\core\auth
	 */
	class User
	{

		/**
		 * @var string $table
		 */
		private $table;
		/**
		 * @var \app\core\database\Database $DB
		 */
		private $DB;
		/**
		 * @var Session $session
		 */
		private $session;
		/**
		 * @var Password $password
		 */
		public $password;
		/**
		 * @var Token $token
		 */
		public $token;
		/**
		 * @var OAuth $oauth
		 */
		public $oauth;

		/**
		 * @var bool|mixed $must_verify
		 */
		public $must_verify = false;

		/**
		 * User constructor
		 * @param $config
		 */
		public function __construct($config) {
			// Get user table
			$this->table = $config['table'];
			// Get useful classes
			$this->DB = Application::$app->database;
			$this->session = Application::$app->session;
			$this->password = new Password();
			$this->token = new Token();
			$this->oauth = new OAuth($config['oauth']);
			// Init must_verify flag
			$this->must_verify = $config['verification'];
		}

		/**
		 * Check if user exists in database
		 * @param $field
		 * @param $value
		 * @return bool
		 */
		public function exists($field, $value) {
			$this->DB->table($this->table);
			$result = $this->DB->select([
				'columns' => ['COUNT(*) AS n'],
				'where' => [
					"$field" => $value
				]
			]);

			return $result[0]['n'] > 0;
		}

		/**
		 * Get user info
		 * @param $field
		 * @param $value
		 * @return array|mixed
		 */
		public function get_info($field = 'id', $value = null) {
			$value = $value ?? $_SESSION['user']['id'];
			$this->DB->table($this->table);
			return $this->DB->select([
				'columns' => ['*'],
				'where' => [
					"$field" => $value
				]
			])[0] ?? [];
		}

		/**
		 * Get user id
		 * @param $field
		 * @param $value
		 * @return mixed|null
		 */
		public function id($field, $value) {
			$this->DB->table($this->table);
			return $this->DB->select([
				'columns' => ['id'],
				'where' => [
					"$field" => $value
				]
			])[0]['id'] ?? null;
		}

		/**
		 * Create new user in database
		 * @param $data
		 * @return int
		 */
		public function create($data) {
			$this->DB->table($this->table);
			return $this->DB->insert($data);
		}

		/**
		 * Verify user credentials and start new user session
		 * @param $data
		 * @return bool
		 */
		public function login($data) {
			// Get data parts
			$fields = array_keys($data);
			$values = array_values($data);

			// If user exists
			if ($this->exists($fields[0], $values[0])) {
				// Get user info
				$user = $this->get_info($fields[0], $values[0]);

				// Verify password
				if ($this->password->verify($values[1], $user[$fields[1]])) {
					// Create new User session
					$this->new_session([
						'id' => $user['id'],
						$fields[0] => $values[0]
					]);
					return true;
				}
			}

			return false;
		}

		/**
		 * Create new user session
		 * @param array $user
		 */
		public function new_session($user = []) {
			$this->session->put('user', [
				'id' => $user['id'] ?? null,
				array_keys($user)[1] => array_values($user)[1]
			]);
		}

		/**
		 * Activate user account
		 * @param $id
		 * @param $column
		 */
		public function activate($id, $column) {
			$this->update([$column => 1], ['id' => $id]);
		}

		/**
		 * Check if user's account is activated
		 * @param $field
		 * @param $value
		 * @param $column
		 * @return bool
		 */
		public function is_active($field, $value, $column) {
			$user = $this->get_info($field, $value);
			return $user[$column] && $user[$column] == 1;
		}

		/**
		 * Check if user has a token with the given type
		 * @param $id
		 * @param $type
		 * @return bool
		 */
		public function has_token($id, $type) {
			$this->DB->table('tokens');
			$token = $this->DB->select([
				'where' => ['user' => $id, 'type' => $type]
			]);

			if (count($token) > 0) {
				return !$this->token->expired($token[0]['expired_at']);
			}

			return false;
		}

		/**
		 * Update user info
		 * @param $data
		 * @param $where
		 */
		public function update($data, $where) {
			$this->DB->table($this->table);
			$this->DB->update($data, $where);
		}

		/**
		 * Remember user in a cookie, to login again
		 * @param $id
		 */
		public function remember($id) {
			// Get remember token from DB, or generate new one
			$token = $this->token->get($id, 'r') ?? $this->token->generate()->store($id, 'r');

			// Set remember cookie
			setcookie('id', $id, time() + 3600 * 24 * 30);
			setcookie('token', $token, time() + 3600 * 24 * 30);
		}

		/**
		 * Destroy remember user cookie
		 */
		public function forget() {
			// Get remember cookie data
			$id = $_COOKIE['id'] ?? null;
			$token = $_COOKIE['token'] ?? null;

			// Remove remember cookie from DB
			$this->token->remove($id, $token, 'r');

			// Remove remember cookie
			setcookie('id', $id, time() - 3600);
			setcookie('token', $token, time() - 3600);
		}
	}