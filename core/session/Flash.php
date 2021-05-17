<?php


	namespace app\core\session;


	/**
	 * Class Flash
	 * This class is used to deal with flash messages
	 * @package app\core\session
	 */
	class Flash
	{

		/**
		 * Flash constructor
		 */
		public function __construct() {
			// Check if there is flash messages
			if (isset($_SESSION['flash']) && is_array($_SESSION['flash'])) {
				// Loop through flash messages
				foreach ($_SESSION['flash'] as $key => $flash) {
					// Decrement usage times of the message
					$_SESSION['flash'][$key]['usage']--;
				}
			}
		}

		/**
		 * Set flash messages
		 * @param $key
		 * @param $value
		 * @param int $usage
		 */
		public function set($key, $value, $usage = 1) {
			$_SESSION['flash'][$key] = [
				'value' => $value,
				'usage' => $usage
			];
		}

		/**
		 * Get flash message content
		 * @param $key
		 * @return mixed|null
		 */
		public function get($key) {
			if (isset($_SESSION['flash'][$key])) {
				return $_SESSION['flash'][$key]['value'] ?? null;
			}

			return null;
		}

		/**
		 * Delete flash message by key
		 * @param $key
		 */
		public function unset($key) {
			if (isset($_SESSION['flash'][$key])) unset($_SESSION['flash'][$key]);
		}

		/**
		 * Remove all used flash messages
		 */
		public function __destruct() {
			// If there is flash messages
			if (isset($_SESSION['flash']) && is_array($_SESSION['flash'])) {
				// Loop through them
				foreach ($_SESSION['flash'] as $key => $flash) {
					// If the message reaches usage times limit
					if ($_SESSION['flash'][$key]['usage'] <= 0) {
						// Delete
						$this->unset($key);
					}
				}
			}

			// If there is no message left
			if (isset($_SESSION['flash']) && count($_SESSION['flash']) == 0) {
				// Remove flash session
				unset($_SESSION['flash']);
			}
		}

	}