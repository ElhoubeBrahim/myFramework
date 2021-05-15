<?php


	namespace app\core\auth;


	use app\core\Application;

	/**
	 * Class Token
	 * @package app\core\auth
	 */
	class Token
	{

		/**
		 * @var string $token
		 */
		private $token;

		/**
		 * Generate new token
		 * @return $this
		 */
		public function generate() {
			$this->token = bin2hex(random_bytes(64));
			return $this;
		}

		/**
		 * Store and return generated token
		 * @param $id
		 * @param $type
		 * @return string
		 */
		public function store($id, $type) {
			Application::$app->database->table('tokens');
			Application::$app->database->insert([
				'user' => $id,
				'token' => $this->token,
				'type' => $type,
				'expired_at' => date('Y-m-d h:m:s', strtotime('+1 day'))
			]);

			return $this->token;
		}

		/**
		 * Get token from database
		 * @param $id
		 * @param $type
		 * @return string|null
		 */
		public function get($id, $type) {
			return Application::$app->database->select([
					'table' => 'tokens',
					'where' => [
						'user' => $id,
						'type' => $type
					]
				])[0]['token'] ?? null;
		}

		/**
		 * Verify token
		 * @param $id
		 * @param $token
		 * @param $type
		 * @return bool
		 */
		public function verify($id, $token, $type) {
			$result = Application::$app->database->select([
				'table' => 'tokens',
				'where' => [
					'user' => $id,
					'token' => $token,
					'type' => $type
				]
			]);

			if (count($result) > 0) {
				return !$this->expired($result[0]['expired_at']);
			}

			return false;
		}

		/**
		 * Remove token from database
		 * @param $id
		 * @param $token
		 * @param $type
		 */
		public function remove($id, $token, $type) {
			Application::$app->database->table('tokens');
			Application::$app->database->delete([
				'user' => $id,
				'token' => $token,
				'type' => $type
			]);
		}

		/**
		 * Check expiration date of token
		 * @param $date
		 * @return bool
		 */
		public function expired($date) {
			$date = new \DateTime($date);
			$now = new \DateTime();
			return $now > $date;
		}

	}