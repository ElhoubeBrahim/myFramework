<?php


	namespace app\core\auth;


	use app\core\Application;

	/**
	 * Class Password
	 * This class is used to deal with passwords
	 * and secret informations
	 * @package app\core\auth
	 */
	class Password
	{

		/**
		 * Hash password
		 * @param $password
		 * @return false|string|null
		 */
		public function hash($password) {
			return password_hash($password, PASSWORD_DEFAULT);
		}

		/**
		 * Verify password
		 * @param $password
		 * @param $hash
		 * @return bool
		 */
		public function verify($password, $hash) {
			return password_verify($password, $hash);
		}

		/**
		 * Update user password
		 * @param $id
		 * @param $password
		 */
		public function update($id, $password) {
			// Get user class
			$User = Application::$app->user;
			// Hash password
			$password = $this->hash($password);
			// Update user info
			$User->update(['password' => $password], ['id' => $id]);
		}

	}