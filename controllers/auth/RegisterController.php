<?php


	namespace app\controllers\auth;
	use app\core\Application;
	use app\core\mvc\Controller;
	use app\models\auth\LoginModel;
	use app\models\auth\RegisterModel;

	class RegisterController extends Controller
	{

		public function render($req, $res) {
			$res->set_layout('main');
			$res->render('auth/register', [
				'title' => 'My Framework | Register'
			]);
		}

		public function register($req, $res) {
			// Sanitize sent data
			$req->body = Application::sanitize($req->body);
			$User = new RegisterModel([
				'name' => $req->body['name'],
				'email' => $req->body['email'],
				'password' => $req->body['password']
			]);

			// Validate user data
			$User->validate();

			// Register user
			$User->register();

			// Get login model
			$User = new LoginModel([
				'email' => $req->body['email'],
				'password' => $req->body['password']
			]);

			// Verify new account
			$User->verify($req, $res);

			// Login user
			$User->login($req, $res);

			// Redirect to the dashboard
			$res->redirect('/profile');
		}

	}