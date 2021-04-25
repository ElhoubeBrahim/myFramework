<?php


	namespace app\core\form;
	use app\core\Application;

	/**
	 * Class Form
	 * This class is used to create HTML forms
	 * @package app\core\form
	 */
	class Form
	{
		/**
		 * Form fields
		 * @var array $fields
		 */
		private $fields = [];
		/**
		 * Request instance
		 * @var \app\core\router\Request $request
		 */
		private $request;

		/**
		 * Form attributes
		 * @var array $attributes
		 */
		private $attributes;
		/**
		 * Invalid class name
		 * @var string $invalid_class
		 */
		public $invalid_class;
		/**
		 * Field container element
		 * @var array $field_container
		 */
		public $field_container;
		/**
		 * Feedback container element
		 * @var array $feedback_container
		 */
		public $feedback_container;

		/**
		 * Form constructor.
		 * @param array $config
		 */
		public function __construct($config = []) {
			// Get the form properties
			$this->attributes = $config['attributes'] ?? ['action' => '', 'method' => 'POST'];
			$this->invalid_class = $config['invalid_class'] ?? 'invalid';
			$this->field_container = $config['field_container'] ?? [null, null];
			$this->feedback_container = $config['feedback_container'] ?? ['<div>', '</div>'];

			// Get the request object
			$this->request = Application::$app->request;
		}

		/**
		 * Output the opening tag of the form
		 */
		public function begin() {
			// Init attributes
			$attributes = [];
			// Loop through given attributes
			foreach ($this->attributes as $attr => $val) {
				// Transform [attr => val] to attr="val"
				$attributes[] = "$attr=\"$val\"";
			}
			// Output the opening tag of the form
			echo sprintf("<form %s>", implode(' ', $attributes));
		}

		/**
		 * Output the closing tag of the form
		 */
		public function end() {
			echo '</form>';
		}

		/**
		 * Create new field
		 * @param array $settings
		 */
		public function field($settings = []) {
			// Create new field object
			$field = new Field($settings, $this);
			// Output the field HTML
			$field->generate();
			// Add field to the form fields
			$this->fields[] = $field;
		}

		/**
		 * Create select field
		 * @param array $settings
		 */
		public function select($settings = []) {
			// Create new field instance
			$field = new Field($settings, $this);
			// Output the select field HTML
			$field->generate_select();
			// Add field to the form fields
			$this->fields[] = $field;
		}
	}