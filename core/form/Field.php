<?php


	namespace app\core\form;


	use app\core\Application;

	/**
	 * Class Field
	 * This class is used to create fields for the form class
	 * @package app\core\form
	 */
	class Field
	{
		/**
		 * Request instance
		 * @var \app\core\router\Request $request
		 */
		private $request;

		// Field properties
		private $tag;
		private $type;
		private $name;
		private $attributes;
		private $value;
		private $label;
		private $container;
		private $invalid;
		private $invalid_class;
		private $feedback;
		private $feedback_container;

		// Select field properties
		private $options;
		private $default;

		/**
		 * Field constructor.
		 * @param $settings
		 * @param $form
		 */
		public function __construct($settings, $form) {
			// Get the request object
			$this->request = Application::$app->request;

			// Get field properties
			$this->tag = $settings['tag'] ?? 'input';
			$this->type = $settings['type'] ?? 'text';
			$this->name = $settings['name'] ?? null;
			$this->attributes = $settings['attributes'] ?? [];
			$this->value = $settings['value'] ?? $this->value($this->name);
			$this->label = $settings['label'] ?? null;
			$this->container = $settings['container'] ?? $form->field_container;
			$this->invalid = $settings['invalid'] ?? isset($this->request->errors()[rtrim($this->name, '[]')]);
			$this->invalid_class = $settings['invalid_class'] ?? $form->invalid_class;
			$this->feedback = $settings['feedback'] ?? $this->request->errors()[rtrim($this->name, '[]')][0]  ?? null;
			$this->feedback_container = $settings['feedback_container'] ?? $form->feedback_container;

			// Get the select field properties
			$this->options = $settings['options'] ?? [];
			$this->default = $settings['default'] ?? null;
		}

		/**
		 * Get the value of field by name frome the request body
		 * @param $name
		 * @return string|null
		 */
		private function value($name) {
			return $this->request->body[$name] ?? null;
		}

		/**
		 * Generate field label
		 */
		private function generate_label() {
			// If there is label
			if ($this->label != null) {
				// Output the label HTML
				echo sprintf("<label for='%s'>%s</label>", $this->name, $this->label);
			}
		}

		/**
		 * Get field attributes
		 * @return string
		 */
		private function get_attributes() {
			// Init attributes
			$attributes = [];

			// If the field is input
			if ($this->tag == "input") {
				// If there a default value, set it
				if ($this->value != null) $attributes[] = "value=\"$this->value\"";
				// Set the input type
				$attributes[] = "type=\"$this->type\"";
			}

			// Set the field name and id
			$attributes[] = "name=\"$this->name\"";
			$attributes[] = "id=\"" . rtrim($this->name, '[]') . "\"";

			// Loop trough given attributes
			foreach ($this->attributes as $attr => $val) {
				// Transform [attr => val] to attr="val"
				$attributes[] = "$attr=\"$val\"";
			}

			// Return field attributes => attr1="val1" attr2="val2" ...
			return implode(' ', $attributes);
		}

		/**
		 * Get invalid class name
		 */
		private function get_invalid_class() {
			// If the field is invalid
			if ($this->invalid) {
				// If the field has class attributr
				if (isset($this->attributes['class'])) {
					// Append the invalid class name to the field classes
					$this->attributes['class'] .= ' ' . $this->invalid_class;
				} else {
					// Set class to invalid class name
					$this->attributes['class'] = $this->invalid_class;
				}
			}
		}

		/**
		 * Generate field feedback
		 */
		private function generate_feedback() {
			// If the field is invalid
			if ($this->invalid) {
				// Output the feedback HTML
				echo $this->feedback_container[0];
				echo $this->feedback;
				echo $this->feedback_container[1];
			}
		}

		/**
		 * Generate field
		 */
		private function generate_field() {
			// Set invalid class
			$this->get_invalid_class();
			// Get attributes text
			$attributes = $this->get_attributes();
			// Output the opening tag of the field
			echo sprintf("<%s %s>", $this->tag, $attributes);
			// If the field is textarea or button
			if ($this->tag == "textarea" or $this->tag == "button") {
				// Output the value inside it
				echo $this->value;
			}
		}

		/**
		 * Generate options for select field
		 */
		private function generate_options() {
			// Init i
			$i = 0;
			// Loop through options
			foreach ($this->options as $key => $val) {
				// Get the selected attribute
				$selected = ($this->default === $i + 1) ? 'selected' : null;
				// Output the option HTML
				echo sprintf("<option value='%s' %s>%s</option>", $key, $selected, $val);
				// Increment i for the next iteration
				$i++;
			}
		}

		/**
		 * Close the field tag
		 */
		private function close_field() {
			// If the field was not input
			if ($this->tag != "input") {
				// Close tag
				echo "</$this->tag>";
			}
		}

		/**
		 * Generate the field HTML
		 */
		public function generate() {
			// Echo the opening tag of field container
			echo $this->container[0] ?? '';
			// Generate field label
			$this->generate_label();
			// Generate field
			$this->generate_field();
			// Close field tag
			$this->close_field();
			// Generate validation feedback
			$this->generate_feedback();
			// Echo the closing tag of field container
			echo $this->container[1] ?? '';
		}

		/**
		 * Generate the select field HTML
		 */
		public function generate_select() {
			// Set the tag
			$this->tag = 'select';
			// Echo the opening tag of field container
			echo $this->container[0] ?? '';
			// Generate field label
			$this->generate_label();
			// Generate field
			$this->generate_field();
			// Generate select options
			$this->generate_options();
			// Close field tag
			$this->close_field();
			// Generate validation feedback
			$this->generate_feedback();
			// Echo the closing tag of field container
			echo $this->container[1] ?? '';
		}

	}