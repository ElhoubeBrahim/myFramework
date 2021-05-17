<?php


	namespace app\core\mvc;


	use app\core\Application;
	use app\core\database\Database;

	/**
	 * Class Model
	 * @package app\core\mvc
	 */
	class Model extends Database
	{

		public function __construct() {
			$provider = Application::$app->config['database']['provider'];
			parent::__construct(Application::$app->config['database'][$provider]);
		}

		public function rules() {
			return [];
		}

		public function validate() {
			$valid = Application::$app->request->validate($this->rules());
			if (!$valid && method_exists($this, 'invalid')) {
				$this->invalid(Application::$app->request, Application::$app->response);
			}
		}

		public function load($data) {
			if (is_array($data)) {
				foreach ($data as $attr => $val) {
					$this->{$attr} = $val;
				}
			}
		}

	}