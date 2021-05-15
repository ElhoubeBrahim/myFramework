<?php


	namespace app\core\auth;


	use app\core\Application;

	/**
	 * Class Auth
	 * This class is used to perform authentication actions
	 * @package app\core\auth
	 */
	class Auth
	{

		/**
		 * @var User $user
		 */
		private $user;
		/**
		 * @var Session $session
		 */
		private $session;

		/**
		 * Auth constructor
		 */
		public function __construct() {
			// Get instances
			$this->user = Application::$app->user;
			$this->session = Application::$app->session;
		}

		/**
		 * Check if user logged in
		 * @return bool
		 */
		public function auth() {
			// Check if user has not a session
			if (!$this->session->has('user')) return false;

			// Get the user from session
			$user = $this->session->get('user');
			$field = array_keys($user)[1] ?? null;
			$value = array_values($user)[1] ?? null;

			// If user does not exists
			if (!$this->user->exists($field, $value)) {
				return false;
			}

			// Else
			return true;
		}

		/**
		 * Check if user is not logged in
		 * @return bool
		 */
		public function guest() {
			// If user has session, return false
			if ($this->session->has('user')) return false;
			// If user is Authed return fales
			if ($this->auth()) return false;
			// Else, return true
			return true;
		}

		/**
		 * Destroy the user session
		 */
		public function destroy() {
			$this->session->unset('user');
		}

	}