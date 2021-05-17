<?php


	namespace app\core\auth;

	use app\core\Application;

	/**
	 * Class OAuth
	 * This class is used to handle 3rd party authorization - OAuth 2
	 * @package app\core\auth
	 */
	class OAuth
	{

		public static $config;

		/**
		 * OAuth constructor
		 * @param $config
		 */
		public function __construct($config) {
			self::$config = $config;
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

	}