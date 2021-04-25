<?php

	namespace app\core\mvc;

	use app\core\Application;

	/**
	 * Class Controller
	 * @package app\core\mvc
	 */
	class Controller
	{

		/**
		 * @var array $middlewares
		 */
		private $middlewares = [];
		/**
		 * @var \app\core\database\Database $DB
		 */
		protected $DB;

		/**
		 * Controller constructor.
		 */
		public function __construct() {
			// Get database object
			$this->DB = Application::$app->database;
		}

		/**
		 * Register middleware for the target controller
		 * @param $middleware
		 */
		protected function register_middleware($middleware) {
			$this->middlewares[] = $middleware;
		}

		/**
		 * Get middlewares of the target controller
		 * @return array
		 */
		public function get_middlewares() {
			return $this->middlewares;
		}

	}