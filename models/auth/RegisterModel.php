<?php


	namespace app\models\auth;

	use app\core\Application;
	use app\core\mvc\Model;
	use app\controllers\auth\RegisterController;

	class RegisterModel extends Model
	{

		private $User;

		protected $name;
		protected $email;
		protected $password;

		public function __construct($data) {
			// Run parents constructors
			parent::__construct();
			// Get user
			$this->User = Application::$app->user;
			// Load model data
			$this->load($data);
		}

		public function rules() {
			return [
				'name' => ['required', 'min_length:3', 'max_length:255'],
				'email' => ['required', 'email', 'max_length:255', 'unique:users'],
				'password' => ['required', 'min_length:8']
			];
		}

		public function register() {
			$this->User->create([
				'name' => $this->name,
				'email' => $this->email,
				'password' => $this->User->password->hash($this->password)
			]);
		}

		public function invalid($req, $res) {
			$req->session->flash('error', 'Validation error', 0);
			$controller = new RegisterController();
			$controller->render($req, $res);
			return;
		}

	}