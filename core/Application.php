<?php


	namespace app\core;

	/**
	 * Class Application
	 * This is the main class, it acts like a glue for all other needed classes
	 * @package app\core
	 */
	class Application
	{

		// Application properties
		public $dir;
		public $uploads_dir;
		public  $url;
		public $config;
		public static $app;

		// Application main classes
		public $session;
		public $mailer;
		public $router;
		public $request;
		public $response;
		public $view;
		public $controller;
		public $database;
		public $user;
		public $auth;

		// Application lang properties
		public $lang;
		public $lang_path;
		public $dictionary;

		/**
		 * Application constructor
		 * @param $dir
		 * @param array $config
		 */
		public function __construct($dir, $config = []) {
			// Get application properties
			$this->config = $config;
			$this->dir = $dir;
			$this->uploads_dir = $dir . $config['app']['uploads'] ?? '/';
			$this->url = $config['app']['url'] ?? '/';
			self::$app = $this;

			// Get application lang directories
			$this->lang = $config['app']['lang'] ?? 'en';
			$this->lang_path = "$dir/lang/$this->lang";
			$this->dictionary = require("$this->lang_path/$this->lang.php");

			// Instantiate main classes
			$this->session = new session\Session();
			$this->mailer = new mailer\Mail($config['mailer']);
			$this->request = new router\Request();
			$this->response = new router\Response();
			$this->router = new router\Router($this->request, $this->response);
			$this->view = new mvc\View((isset($config['views'])) ? $dir . '/' . $config['views'] : $dir . '/views');
			$this->controller = new mvc\Controller();
			$provider = $config['database']['provider'];
			$this->database = new database\Database($config['database'][$provider] ?? []);
			$this->user = new auth\User($config['auth']);
			$this->auth = new auth\Auth();
		}

		/**
		 * Sanitize data
		 * @param $data
		 * @return array|string
		 */
		public static function sanitize($data) {
			// If data is array
			if (is_array($data)) {
				// Loop through data
				foreach ($data as $key => $value) {
					// Sanitize it
					$data[$key] = self::clean($value);
				}
			}

			// If data is string
			if (is_string($data)) {
				// Sanitize it
				$data = self::clean($data);
			}

			// Return sanitized data
			return $data;
		}

		/**
		 * Sanitize strings
		 * @param $string
		 * @return string
		 */
		private static function clean($string) {
			$string = htmlspecialchars($string);
			$string = stripslashes($string);
			$string = trim($string);
			return $string;
		}

		/**
		 * Run the application
		 */
		public function run() {
			// Resolve the requested route
			$this->router->resolve();
		}

	}