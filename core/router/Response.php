<?php

	namespace app\core\router;
	use app\core\Application;

	class Response
	{

		/**
		 * Set response status code
		 * @param $code
		 */
		public function set_status_code($code) {
			// If the code is integer
			if (filter_var($code, FILTER_VALIDATE_INT)) {
				// Set the response code
				http_response_code($code);
			}
		}

		/**
		 * Redirect to a route
		 * @param $route
		 */
		public function redirect($route) {
			header("Location: $route");
			exit();
		}

		/**
		 * Write text in the response area
		 * @param $response
		 */
		public function write($response) {
			echo htmlentities($response);
		}

		/**
		 * Write HTML to the response area
		 * @param $response
		 */
		public function html($response) {
			echo $response;
		}

		/**
		 * Output JSON object
		 * @param $object
		 */
		public function json($object) {
			// If the object is array
			if (is_array($object)) {
				// Set the content type header to JSON
				header('Content-Type: application/json');
				// Output the JSON object
				echo json_encode($object);
				// Exit
				exit();
			}
		}

		/**
		 * Output message and exit
		 * @param $message
		 */
		public function end($message) {
			echo $message;
			exit();
		}

		/**
		 * Set layout
		 * @param $layout
		 * @return $this
		 */
		public function set_layout($layout) {
			Application::$app->view->set_layout($layout);
			return $this;
		}

		/**
		 * Render view
		 * @param $view
		 * @param array $data
		 */
		public function render($view, $data = []) {
			Application::$app->view->render($view, $data);
		}
	}