<?php


	namespace app\core\validation;

	use app\core\Application;

	/**
	 * Class Validator
	 * This class is used to validate request data
	 * @package app\core\validation
	 */
	class Validator extends Rules
	{

		/**
		 * Define rules
		 * @var string[]
		 */
		private $rules = [
			'email', 'url',
			'required', 'unique',
			'min', 'max', 'between',
			'length', 'min_length', 'max_length',
			'words', 'min_words', 'max_words',
			'numeric', 'alpha', 'alpha_numeric',
			'same', 'different',
			'regex', 'not_regex',
			'unique',
			'file', 'image', 'extensions', 'accept',
			'size', 'max_size', 'min_size'
		];

		/**
		 * Validator constructor
		 * @param $req
		 */
		public function __construct($req) {
			// Get validation messages
			$this->messages = Application::$app->dictionary['validation'] ?? [];
			// Get the request instance
			$this->request = $req;
		}

		/**
		 * Validates given data according to the given rule
		 * @param $data
		 * @param $rule
		 */
		public function validate($data, $rule) {
			// Get rule
			$rule = trim($rule);
			$rule = explode(':', $rule);
			// If the given rule is a valid rule
			if (in_array($rule[0], $this->rules)) {
				// Get rule arguments
				$args = (count($rule) > 1) ? array_slice($rule, 1) : [];
				// Append data to the arguments array
				array_unshift($args, $data);
				// Call the appropriate rule validation method
				call_user_func_array([$this, $rule[0]], $args);
			}
		}
	}