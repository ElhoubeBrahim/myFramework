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
		private $views_path;
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

		/**
		 * Get the content of selected layout
		 * @param $layout
		 * @param $data
		 * @return false|string
		 */
		private function get_layout_content($layout, $data) {
			// Define parsed variables
			foreach ($data as $var => $value) {
				$$var = $value;
			}

			// Open the buffer
			ob_start();
			// Include the layout file
			include("$this->views_path/layouts/$layout.php");
			// Clean the buffer and get content
			return ob_get_clean();
		}

		/**
		 * Get the content of selected view
		 * @param $view
		 * @param $data
		 * @return false|string
		 */
		private function get_view_content($view, $data) {
			// Define parsed variables
			foreach ($data as $var => $value) {
				$$var = $value;
			}

			// Open the buffer
			ob_start();
			// Include the view
			include("$this->views_path/$view.php");
			// Clean the buffer and get content
			return ob_get_clean();
		}

		/**
		 * Get the full view content
		 * @param $view
		 * @param $data
		 * @return string
		 */
		private function get_content($view, $data) {
			// Get the selected view content
			$view_content = $this->get_view_content($view, $data);

			// If the layout is valid
			if (is_string($this->layout) and $this->layout != null) {
				// Get the selected layout content
				$layout_content = $this->get_layout_content($this->layout, $data);
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
			$content = $this->get_content($view, $data);
			// Output the content
			echo $content;
			// Init layout
			$this->set_layout(null);
			// Exit
			exit();
		}

	}