<?php

	namespace app\core\router;
	use app\core\Application;
	use app\core\filesystem\FS;
	use app\core\validation\Validator;

	/**
	 * Class Request
	 * This class is used to handle incoming requests and data
	 * @package app\core\router
	 */
	class Request
	{

		/**
		 * GET params
		 * @var array $params
		 */
		public $params = [];
		/**
		 * Sent data
		 * @var array $body
		 */
		public $body = [];
		/**
		 * Uploaded files
		 * @var array $files
		 */
		public $files = [];
		/**
		 * Validator instance
		 * @var Validator $validator
		 */
		private $validator;
		/**
		 * FS instance
		 * @var FS $fs
		 */
		public $fs;

		/**
		 * Request constructor
		 */
		public function __construct() {
			// Loop through GET params
			foreach ($_GET as $key => $value) {
				// Set request params
				$this->params[$key] = $value;
				// Remove the param from super global GET
				unset($_GET[$key]);
			}

			// If the request method was POST
			if (strtoupper($this->method()) === "POST") {
				// Parse incoming data to the request body
				$this->parse_body();
			}

			// If there is incoming files
			if (!empty($_FILES)) {
				// Parse incoming files to the request files
				$this->parse_files();
			}

			// Create new validator
			$this->validator = new Validator($this);
			// Create new FS instance
			$this->fs = new FS(Application::$app->uploads_dir);
		}

		/**
		 * Get request method
		 * @return string
		 */
		public function method() {
			return $_SERVER["REQUEST_METHOD"];
		}

		/**
		 * Get the request path/url
		 * @return string
		 */
		public function path() {
			// Get the request uri
			$path = $_SERVER["REQUEST_URI"];
			// Get the question mark position
			$position = strpos($_SERVER['REQUEST_URI'], '?');
			// Remove url params ?foo=bar&bar=foo
			if ($position != false) $path = substr($path, 0, $position);
			// Remove the last slash from the path "/u/test/" => "/u/test"
			$path = ($path != '/') ? rtrim($path, '/') : $path;
			// Return path
			return $path;
		}

		/**
		 * Parse multiple request params
		 * @param $params
		 */
		public function parse_params($params) {
			// Loop through params
			foreach ($params as $key => $value) {
				// Set single param
				$this->set_param($key, $value);
			}
		}

		/**
		 * Set request param
		 * @param $key
		 * @param $value
		 */
		public function set_param($key, $value) {
			$this->params[$key] = $value;
		}

		/**
		 * Get all request params
		 * @param $key
		 * @return array|null
		 */
		public function get_param($key) {
			return $this->params[$key] ?? null;
		}

		/**
		 * Parse the request body
		 */
		public function parse_body() {
			// Loop through incoming data
			foreach ($_POST as $key => $val) {
				// Add data to the request body
				$this->body[$key] = $val;
				// Remove the param from super global POST
				unset($_POST[$key]);
			}
		}

		/**
		 * Parse the request files
		 */
		private function parse_files() {
			// Loop through uploaded files
			foreach ($_FILES as $name => $file) {
				// Add the file to the request files
				$this->files[$name][0] = $file;
				// If the user uploads multiple files
				if (is_array($file['tmp_name'])) {
					// Empty the request files name
					$this->files[$name] = [];
					// Loop through uploaded files
					for ($i = 0; $i < count($file['tmp_name']); $i++) {
						// Set request files properties
						$this->files[$name][$i]['name'] = $file['name'][$i];
						$this->files[$name][$i]['type'] = $file['type'][$i];
						$this->files[$name][$i]['size'] = $file['size'][$i];
						$this->files[$name][$i]['tmp_name'] = $file['tmp_name'][$i];
						$this->files[$name][$i]['error'] = $file['error'][$i];
					}
				}
				// Remove the file from super global FILES
				unset($_FILES[$name]);
			}
		}

		/**
		 * Get the file by name
		 * @param $name
		 * @return array
		 */
		public function file($name) {
			return $this->files[$name][0] ?? [];
		}

		/**
		 * Validate sent data
		 * @param $data
		 * @return bool
		 */
		public function validate($data) {
			// Loop through validation rules
			foreach ($data as $name => $rules) {
				// Get rules
				$rules = (is_string($rules)) ? explode('|', $rules) : $rules;
				// Loop through rules
				foreach ($rules as $rule) {
					// Validate the field using Validator class
					$this->validator->validate(
						[$name, $this->body[$name] ?? $this->files[$name] ?? null], $rule
					);
				}
			}

			// return if data are valid
			return !$this->validator->invalid;
		}

		/**
		 * Get validation errors
		 * @return array
		 */
		public function errors() {
			return $this->validator->get_errors();
		}

	}