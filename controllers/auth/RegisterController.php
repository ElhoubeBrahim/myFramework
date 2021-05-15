<?php


	namespace app\controllers\auth;
	use app\core\Application;
	use app\core\mvc\Controller;

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

			// Validate user data
			$valid = $req->validate([
				'name' => ['required', 'min_length:3', 'max_length:255'],
				'email' => ['required', 'email', 'max_length:255', 'unique:users'],
				'password' => ['required', 'min_length:8']
			]);

			// If it is invalid
			if (!$valid) {
				// Render the register page with errors
				$this->render($req, $res);
				return;
			}

			// Else
			// Get user class
			$User = Application::$app->user;

			// Create new user account
			$User->create([
				'name' => $req->body['name'],
				'email' => $req->body['email'],
				'password' => $User->password->hash($req->body['password'])
			]);

			if ($User->must_verify) {
				$req->session->put('email', $req->body['email']);
				$res->redirect("/verify/email");
			}

			// Login
			$logged_in = $User->login([
				'email' => $req->body['email'],
				'password' => $req->body['password']
			]);

			if (!$logged_in) {
				$res->redirect('/login');
			}

			// Redirect to the dashboard
			$res->redirect('/dashboard');
		}

	}