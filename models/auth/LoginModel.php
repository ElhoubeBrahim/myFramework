<?php


	namespace app\models\auth;

	use app\core\Application;
	use app\core\mvc\Model;
	use app\controllers\auth\LoginController;

	class LoginModel extends Model
	{

		private $User;

		protected $email;
		protected $password;

		/**
		 * LoginModel constructor
		 * @param $data
		 */
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
				'email' => ['required', 'email', 'max_length:255'],
				'password' => ['required']
			];
		}

		public function login($req, $res) {
			// Authenticate user
			$logged_in = $this->User->login([
				'email' => $this->email,
				'password' => $this->password
			]);

			// If sent credentials are wrong
			if (!$logged_in) {
				// Render the login page with error
				$req->session->flash('error', 'Wrong credentials');
				$res->redirect('/login');
				return;
			}
		}

		public function verify($req, $res) {
			// If account is not verified
			if (
				$this->User->must_verify &&
				!$this->User->is_active('email', $this->email, 'active')
			) {
				// Destroy user session
				Application::$app->auth->destroy();
				// Redirect to the verification page
				$req->session->put('email', $this->email);
				$res->redirect('/verify/email');
			}
		}

		public function remember($req, $res) {
			if (
				isset($req->body['remember']) &&
				($req->body['remember'] == 1 ||
					$req->body['remember'] == 'on')
			) {
				$id = $this->User->id('email', $this->email);
				$this->User->remember($id);
			}
		}

		public function invalid($req, $res) {
			$req->session->flash('error', 'Validation failed', 0);
			$controller = new LoginController();
			$controller->render($req, $res);
			return;
		}
	}