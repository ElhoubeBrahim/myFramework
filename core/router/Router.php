<?php


	namespace app\core\router;

	use app\core\Application;

	/**
	 * Class Router
	 * This class is used to handle routes, middlewares, requests and responses
	 * @package app\core\router
	 */
	class Router
	{
		/**
		 * Static routes array
		 * @var array[]
		 */
		private $static_routes = [
			"GET" => [],
			"POST" => []
		];
		/**
		 * Dynamic routes array
		 * @var array[]
		 */
		private $dynamic_routes = [
			"GET" => [],
			"POST" => []
		];
		/**
		 * @var array
		 */
		private $middlewares = [];
		/**
		 * Request instance
		 * @var Request
		 */
		private $request;
		/**
		 * Response instance
		 * @var Response
		 */
		private $response;
		/**
		 * @var array
		 */
		private $last_inserted_route = [];

		/**
		 * Router constructor
		 * @param $request
		 * @param $response
		 */
		public function __construct($request, $response) {
			$this->request = $request;
			$this->response = $response;
		}

		/**
		 * Add a route with GET method
		 * @param $path
		 * @param $callback
		 * @return $this
		 */
		public function get($path, $callback) {
			// If callback is valid
			if ($callback) {
				// If the route is dynamic
				if (strpos($path, '/:') !== false) {
					// Add the route
					$this->dynamic_routes['GET'][$path]['callback'] = $callback;
					$this->last_inserted_route = ['GET', $path, 'dynamic'];
				} else {
					// Add the route
					$this->static_routes['GET'][$path]['callback'] = $callback;
					$this->last_inserted_route = ['GET', $path, 'static'];
				}
			}

			// Return instance
			return $this;
		}

		/**
		 * Add a route with POST method
		 * @param $path
		 * @param $callback
		 * @return $this
		 */
		public function post($path, $callback) {
			// If callback is valid
			if ($callback) {
				// If the route is dynamic
				if (strpos($path, '/:') !== false) {
					// Add the route
					$this->dynamic_routes['POST'][$path]['callback'] = $callback;
					$this->last_inserted_route = ['POST', $path, 'dynamic'];
				} else {
					// Add the route
					$this->static_routes['POST'][$path]['callback'] = $callback;
					$this->last_inserted_route = ['POST', $path, 'static'];
				}
			}

			// Return instance
			return $this;
		}

		/**
		 * Search for the path in the static routes
		 * @param $path
		 * @param $method
		 * @return callable|null
		 */
		private function check_static_routes($path, $method) {
			// Get middlewares
			$this->middlewares = $this->static_routes[$method][$path]['middlewares'] ?? [];
			// Get the route callback
			return $this->static_routes[$method][$path]['callback'] ?? null;
		}

		/**
		 * Search for the path in the dynamic routes
		 * @param $path
		 * @param $method
		 * @return callable|null
		 */
		private function check_dynamic_routes($path, $method) {
			// Loop through dynamic routes
			foreach ($this->dynamic_routes[$method] as $route => $actions) {
				// Get the route parts "/blog/post/slug" => ["blog", "post", "slug"]
				$route_parts = explode('/', $route);
				// Get the given path part
				$path_parts = explode('/', $path);
				// Init params container
				$params = [];

				// If the given path parts count matches the current route parts count
				if (count($route_parts) === count($path_parts)) {
					// Init i
					$i = 0;
					// Loop through current route parts
					foreach ($route_parts as $part) {
						// If the current part is a param name
						if (isset($part[0]) and $part[0] === ':') {
							// Store the param given value
							$params[substr($part, 1)] = $path_parts[$i];
							// Transform the part to regex that accepts all chars except "/"
							$route_parts[$i] = '([^\/]+)';
						}
						// Increment i
						$i++;
					}

					// Create regex from the current route
					$regex = '/^' . implode('\/', $route_parts) . '$/i';
					// If the current route matches the given path/url
					if (preg_match($regex, $path)) {
						// Set the obtained params
						$this->request->parse_params($params);
						// Get middlewares
						$this->middlewares = $actions['middlewares'] ?? [];
						// Return the callback
						return $actions['callback'];
					}
				}
			}

			// If there is no route return null
			return null;
		}

		/**
		 * Set middlewares
		 * @param array $middlewares
		 */
		public function middlewares($middlewares = []) {
			// If last inserted route's structure is valid
			if (count($this->last_inserted_route) === 3) {
				// Get last inserted route method
				$method = $this->last_inserted_route[0];
				// Get last inserted route path
				$path = $this->last_inserted_route[1];
				// Get last inserted route type - dynamic or static -
				$type = $this->last_inserted_route[2];

				// if the given middleware is not an array, convert it to array
				if (!is_array($middlewares)) $middlewares = [$middlewares];

				// Loop through middlewares
				foreach ($middlewares as $middleware) {
					switch ($type) {
						// If the type of last inserted route is static
						case 'static':
							// Insert the middleware in the static routes
							$this->static_routes[$method][$path]['middlewares'][] = $middleware;
							break;
						// If the type of last inserted route is dynamic
						case 'dynamic':
							// Insert the middleware in the dynamic routes
							$this->dynamic_routes[$method][$path]['middlewares'][] = $middleware;
							break;
					}
				}

				// Empty last inserted route
				$this->last_inserted_route = [];
			}
		}

		/**
		 * Run middlewares of the current route
		 */
		private function run_middlewares() {
			// Loop through middlewares
			foreach ($this->middlewares as $middleware) {
				// If middleware extends Middleware class
				if (is_subclass_of($middleware, Middleware::class)) {
					// Instantiate the middleware
					$middleware = new $middleware;
					// Execute the run method => Run the middleware
					call_user_func_array([$middleware, 'run'], [$this->request, $this->response]);
				}
			}
		}

		/**
		 * Resolve the requested route
		 */
		public function resolve() {
			// Get request uri
			$path = $this->request->path();
			// Get request method
			$method = $this->request->method();

			// Get callback of requested route
			$fn =
				$this->check_static_routes($path, $method) ??
				$this->check_dynamic_routes($path, $method);

			// If callback is null - Not found
			if ($fn === null) {
				// Set HTTP status coder to 404 - NOT FOUND
				$this->response->set_status_code(404);
				// Render the not found page
				Application::$app->view->set_layout('main');
				Application::$app->view->render('errors/404');
				// Exit
				exit();
			}

			// Else
			// Run middlewares before executing the callback
			$this->run_middlewares();

			// Start executing callback
			// If the callback is an array => [Controller, method]
			if (is_array($fn) and count($fn) == 2) {
				// Instantiate the controller
				$fn[0] = new $fn[0];
				// Get controller middlewares
				$this->middlewares = $fn[0]->get_middlewares() ?? [];
				// Run controller middlewares
				$this->run_middlewares();
				// Execute the callback
				call_user_func_array($fn, [$this->request, $this->response]);
				// Exit
				exit();
			}

			// If the callback is a function or a method
			if (is_callable($fn)) {
				// Call the callback
				call_user_func_array($fn, [$this->request, $this->response]);
				// Exit
				exit();
			}

			// If the callback is a string
			if (is_string($fn)) {
				// Render the view
				$this->response->render($fn);
				// Exit
				exit();
			}
		}
	}