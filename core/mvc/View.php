<?php


	namespace app\core\mvc;
	use app\core\Application;


	/**
	 * Class View
	 * @package app\core\mvc
	 */
	class View
	{

		/**
		 * Path to views
		 * @var $views_path
		 */
		public $views_path;
		/**
		 * Default layout
		 * @var null $layout
		 */
		private $layout = null;

		/**
		 * View constructor.
		 * @param $path
		 */
		public function __construct($path) {
			$this->views_path = $path;
		}

		public function get_content($path, $data) {
			// Define parsed variables
			foreach ($data as $var => $value) {
				$$var = $value;
			}

			// Parse flash messages
			$flash = Application::$app->session->get('flash');
			Application::$app->session->unset('flash');

			// Open the buffer
			ob_start();
			// Include the view
			include($path);
			// Clean the buffer and get content
			return ob_get_clean();
		}

		/**
		 * Get the full view content
		 * @param $view
		 * @param $data
		 * @return string
		 */
		private function output($view, $data) {
			// Get the selected view content
			$view_content = $this->get_content("$this->views_path/$view.php", $data);

			// If the layout is valid
			if (is_string($this->layout) and $this->layout != null) {
				// Get the selected layout content
				$layout_content = $this->get_content("$this->views_path/layouts/$this->layout.php", $data);
				// Replace the content placeholder and return result
				return str_replace('{{ @content }}', $view_content, $layout_content);
			}

			// Return just the view content
			return $view_content;
		}

		/**
		 * Select a layout
		 * @param $layout
		 */
		public function set_layout($layout) {
			$this->layout = $layout;
		}

		/**
		 * Render the view
		 * @param $view
		 * @param array $data
		 */
		public function render($view, $data = []) {
			// Get content
			$content = $this->output($view, $data);
			// Output the content
			echo $content;
			// Init layout
			$this->set_layout(null);
			// Exit
			exit();
		}

	}