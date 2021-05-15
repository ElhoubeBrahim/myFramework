<?php


	namespace app\core\auth;


	/**
	 * Class Session
	 * @package app\core\auth
	 */
	class Session
	{

		/**
		 * Session constructor
		 */
		public function __construct() {
			// Start session
			session_start();
			session_regenerate_id();
		}

		/**
		 * Get session content
		 * @param $key
		 * @return array|null
		 */
		public function all($key) {
			return $_SESSION ?? null;
		}

		/**
		 * Get session content by key
		 * @param $key
		 * @return mixed|null
		 */
		public function get($key) {
			return $_SESSION[$key] ?? null;
		}

		/**
		 * Check if session has key
		 * @param $key
		 * @return bool
		 */
		public function has($key) {
			return isset($_SESSION[$key]);
		}

		/**
		 * Add new content to the session by key
		 * @param $key
		 * @param $value
		 */
		public function put($key, $value) {
			$_SESSION[$key] = $value;
		}

		/**
		 * Remove session content by key
		 * @param $key
		 */
		public function unset($key) {
			if (isset($_SESSION[$key])) unset($_SESSION[$key]);
		}

		/**
		 * Add content to flash messages
		 * @param $key
		 * @param $value
		 */
		public function flash($key, $value) {
			$_SESSION['flash'][$key] = $value;
		}

		/**
		 * Get flash message content
		 * @param $key
		 * @return mixed|null
		 */
		public function get_flash($key) {
			if ($this->has('flash')) {
				return $_SESSION['flash'][$key] ?? null;
			}

			return null;
		}

		/**
		 * Destroy the session
		 */
		public function destroy() {
			session_destroy();
		}

		/**
		 * Empty the session content
		 */
		public function empty() {
			$_SESSION = [];
		}

	}