<?php


	namespace app\core\validation;

	use app\core\filesystem\FS;
	use app\core\router\Request;

	/**
	 * Class Rules
	 * This class is used to contain rules validation methods
	 * @package app\core\validation
	 */
	class Rules
	{

		/**
		 * Validation errors
		 * @var array
		 */
		private $errors = [];
		/**
		 * If data are invalid
		 * @var bool
		 */
		public $invalid = false;
		/**
		 * Validation messages
		 * @var string[]
		 */
		protected $messages;
		/**
		 * Request instance
		 * @var Request
		 */
		protected $request;

		/**
		 * @param $data
		 */
		protected function required($data) {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// If value is empty
			if (empty($value)) {
				// Set required validation error
				$message = $this->messages['required'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 * @param int $min
		 */
		protected function min($data, $min = 0) {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// If the value is less than given minimum
			if (is_numeric($value) and (int)$value < $min) {
				// Set min validation error
				$message = $this->messages['min'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$message = str_replace('{min}', $min, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 * @param int $max
		 */
		protected function max($data, $max = 0) {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// If the value is greater than given maximum
			if (is_numeric($value) and (int)$value > $max) {
				// Set max validation error
				$message = $this->messages['max'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$message = str_replace('{max}', $max, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 * @param int $length
		 */
		protected function length($data, $length = 0) {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// Check if the value length is not equal to the given length
			if (strlen($value) != $length) {
				// Set length validation error
				$message = $this->messages['length'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$message = str_replace('{length}', $length, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 * @param int $min
		 */
		protected function min_length($data, $min = 0) {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// Check if the value length is less than the given min length
			if (strlen($value) < $min) {
				// Set min length validation error
				$message = $this->messages['min_length'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$message = str_replace('{min}', $min, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 * @param int $max
		 */
		protected function max_length($data, $max = 0) {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// Check if the value length is more than the given max length
			if (strlen($value) > $max) {
				// Set max length validation error
				$message = $this->messages['max_size'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$message = str_replace('{max}', $max, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 */
		protected function numeric($data) {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// Check if the value is not numeric
			if (!is_numeric($value)) {
				// Set numeric validation error
				$message = $this->messages['numeric'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 * @param int $min
		 * @param int $max
		 */
		protected function between($data, $min = 0, $max = 0) {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// Check if the given value is not in the given range
			if (is_numeric($value) and ((int)$value < $min or (int)$value > $max)) {
				// Set between validation error
				$message = $this->messages['between'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$message = str_replace('{min}', $min, $message);
				$message = str_replace('{max}', $max, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 */
		protected function alpha($data) {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// Check if the given value is not alpha
			if (!ctype_alpha($value)) {
				// Set alpha validation error
				$message = $this->messages['alpha'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 */
		protected function alpha_numeric($data) {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// Check if the given value is not alpha numeric
			if (!ctype_alnum($value)) {
				// Set alpha numeric validation error
				$message = $this->messages['alpha_numeric'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 * @param string $field
		 */
		protected function same($data, $field = '') {
			// Get field name
			$name = $data[0];
			// Get fields values
			$value_1 = $data[1];
			$value_2 = $this->request->body[$field] ?? null;

			// Check if fields values are the same
			if ($value_2 === null or $value_1 != $value_2) {
				// Set same validation error
				$message = $this->messages['same'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$message = str_replace('{same}', $field, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 * @param string $field
		 */
		protected function different($data, $field = '') {
			// Get field name
			$name = $data[0];
			// Get fields values
			$value_1 = $data[1];
			$value_2 = $this->request->body[$field] ?? null;

			// Check if fields values are different
			if ($value_2 === null or $value_1 == $value_2) {
				// Set different validation error
				$message = $this->messages['different'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$message = str_replace('{same}', $field, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 */
		protected function email($data) {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// Check if sent value is not a valid email address
			if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				// Set email validation error
				$message = $this->messages['email'] ?? '';
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 */
		protected function url($data) {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// Check if sent value is not a valid url
			if (!filter_var($value, FILTER_VALIDATE_URL)) {
				// Set url validation error
				$message = $this->messages['url'] ?? '';
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 * @param string $pattern
		 */
		protected function regex($data, $pattern = '//') {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// Check if the value does not match the given pattern
			if (!preg_match($pattern, $value)) {
				// Set regex validation error
				$message = $this->messages['regex'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$message = str_replace('{pattern}', $pattern, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 * @param string $pattern
		 */
		protected function not_regex($data, $pattern = '//') {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// Check if the value matches the given pattern
			if (preg_match($pattern, $value)) {
				// Set not regex validation error
				$message = $this->messages['not_regex'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$message = str_replace('{pattern}', $pattern, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 * @param string $table
		 * @param string $column
		 */
		protected function unique($data, $table = '', $column = '') {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			$result = [];

			if (count($result) > 0) {
				// Set unique validation error
				$message = $this->messages['unique'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 * @param int $words
		 */
		protected function words($data, $words = 0) {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// Check if the value words count is not equal to the given words count
			if (str_word_count($value) != $words) {
				// Set words validation error
				$message = $this->messages['words'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$message = str_replace('{words}', $words, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 * @param int $min
		 */
		protected function min_words($data, $min = 0) {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// Check if the value words count less than to the given min words count
			if (str_word_count($value) < $min) {
				// Set min words validation error
				$message = $this->messages['min_words'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$message = str_replace('{min}', $min, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 * @param int $max
		 */
		protected function max_words($data, $max = 0) {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// Check if the value words count is greater than the given max words count
			if (str_word_count($value) > $max) {
				// Set max words validation error
				$message = $this->messages['max_words'] ?? '';
				$message = str_replace('{field}', $name, $message);
				$message = str_replace('{max}', $max, $message);
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
			}
		}

		/**
		 * @param $data
		 * @return bool
		 */
		protected function file($data) {
			// Get field name
			$name = $data[0];
			// Get field value
			$value = $data[1];

			// If the given file is not an array
			if (!is_array($value)) {
				// Set file validation error
				$message = $this->messages['file'] ?? '';
				$this->set_error($name, $message);
				// Turn invalid flag on
				$this->invalid = true;
				return false;
			}

			// Loop through given files
			foreach ($value as $file) {
				if (
					// If the file is string
					(is_string($file)) or
					// If there is no tmp_name field
					!isset($file['tmp_name']) or
					// If the file was not uploaded
					((isset($file['error'])) and $file['error'] === UPLOAD_ERR_NO_FILE)
				) {
					// Set file validation error
					$message = $this->messages['file'] ?? '';
					$this->set_error($name, $message);
					// Turn invalid flag on
					$this->invalid = true;
					return false;
				}
			}

			// If the given file is a valid file array
			return true;
		}

		/**
		 * @param $data
		 */
		protected function image($data) {
			if($this->file($data)) {
				// Get field name
				$name = $data[0];
				// Get field value
				$value = $data[1];
				// Set allowed images types
				$allowed_types = ['jpeg', 'png', 'gif', 'bmp', 'webp'];

				// Loop through given files
				foreach ($value as $file) {
					// Get the extension of the file from its mimeType
					$ext = explode('/', mime_content_type($file['tmp_name']))[1];
					// If the extension is not in the allowed images types
					if (!in_array($ext, $allowed_types)) {
						// Set image validation error
						$message = $this->messages['image'] ?? '';
						$this->set_error($name, $message);
						// Turn invalid flag on
						$this->invalid = true;
					}
				}
			}
		}

		/**
		 * @param $data
		 * @param $extensions
		 */
		protected function extensions($data, $extensions) {
			if ($this->file($data)) {
				// Get field name
				$name = $data[0];
				// Get field value
				$value = $data[1];
				// Get allowed extensions
				$extensions = explode(',', $extensions);

				// Loop through given files
				foreach ($value as $file) {
					// Get File System class instance
					$fs = new FS('');
					// Get the mimeType of current file
					$mimeType = mime_content_type($file['tmp_name']);
					// Get extension using the mimeType
					$ext = $fs->get_extension($mimeType);

					// Check if extension is allowed
					if (!in_array($ext, $extensions)) {
						// Set extensions validation error
						$message = $this->messages['extensions'] ?? '';
						$message = str_replace('{extensions}', implode(', ', $extensions), $message);
						$this->set_error($name, $message);
						// Turn invalid flag on
						$this->invalid = true;
					}
				}
			}
		}

		/**
		 * @param $data
		 * @param $mimes
		 */
		protected function accept($data, $mimes) {
			if ($this->file($data)) {
				// Get field name
				$name = $data[0];
				// Get field value
				$value = $data[1];
				// Get allowed mimeTypes
				$allowed = explode(',', $mimes);

				// Loop through given files
				foreach ($value as $file) {
					// Get the file mimeType
					$mimeType = mime_content_type($file['tmp_name']);

					// If the mimeType is not allowed
					if (!in_array($mimeType, $allowed)) {
						// Set accept validation error
						$message = $this->messages['accept'] ?? '';
						$message = str_replace('{mimes}', implode(', ', $allowed), $message);
						$this->set_error($name, $message);
						// Turn invalid flag on
						$this->invalid = true;
					}
				}
			}
		}

		/**
		 * @param $data
		 * @param $size
		 */
		protected function size($data, $size) {
			if ($this->file($data)) {
				// Get field name
				$name = $data[0];
				// Get field value
				$value = $data[1];

				// Loop through given files
				foreach ($value as $file) {
					// If the file size is not equal to the given size
					if ($file['size'] != $size) {
						// Set size validation error
						$message = $this->messages['size'] ?? '';
						$message = str_replace('{size}', $size, $message);
						$this->set_error($name, $message);
						// Turn invalid flag on
						$this->invalid = true;
					}
				}
			}
		}

		/**
		 * @param $data
		 * @param $max
		 */
		protected function max_size($data, $max) {
			if ($this->file($data)) {
				// Get field name
				$name = $data[0];
				// Get field value
				$value = $data[1];

				// If the file size is greater than the given size
				foreach ($value as $file) {
					if ($file['size'] > $max) {
						// Set max size validation error
						$message = $this->messages['max_size'] ?? '';
						$message = str_replace('{max}', $max, $message);
						$this->set_error($name, $message);
						// Turn invalid flag on
						$this->invalid = true;
					}
				}
			}
		}

		/**
		 * @param $data
		 * @param $min
		 */
		protected function min_size($data, $min) {
			if ($this->file($data)) {
				// Get field name
				$name = $data[0];
				// Get field value
				$value = $data[1];

				// Loop through given files
				foreach ($value as $file) {
					// If the file size is less than the given size
					if ($file['size'] < $min) {
						// Set min size validation error
						$message = $this->messages['min_size'] ?? '';
						$message = str_replace('{min}', $min, $message);
						$this->set_error($name, $message);
						// Turn invalid flag on
						$this->invalid = true;
					}
				}
			}
		}

		/**
		 * Set validation error message
		 * @param $name
		 * @param $message
		 */
		protected function set_error($name, $message) {
			$this->errors[$name][] = $message;
		}

		/**
		 * Get validation errors
		 * @return array
		 */
		public function get_errors() {
			return $this->errors;
		}

	}