<?php


	namespace app\controllers\auth;
	use app\core\Application;
	use app\core\mvc\Controller;
	use app\models\auth\password\ForgotPasswordModel;
	use app\models\auth\password\ResetPasswordModel;


	class PasswordController extends Controller
	{

		public function render_forgot($req, $res) {
			$res->set_layout('main');
			$res->render('auth/forgot-password', [
				'title' => 'My Framework | Password Forgotten'
			]);
		}

		public function forgot($req, $res) {
			// Sanitize sent data
			$req->body = Application::sanitize($req->body);
			// Get forgot password model
			$Password = new ForgotPasswordModel(['email' => $req->body['email']]);

			// Validate email
			$Password->validate();

			// Generate and send reset token
			$Password->token();

			// Redirect to home
			$req->session->flash('success', 'We had been sent reset email to ' . $req->body['email'] . ' Please check your inbox');
			$res->redirect('/');
		}

		public function render_reset($req, $res) {
			$req->params = Application::sanitize($req->params);

			$res->set_layout('main');
			$res->render('auth/reset-password', [
				'title' => 'My Framework | Password Reset',
				'id' => $req->params['id'],
				'token' => $req->params['token']
			]);
		}

		public function reset($req, $res) {
			// Sanitize sent data
			$req->body = Application::sanitize($req->body);
			$req->params = Application::sanitize($req->params);

			// Get reset password model
			$Password = new ResetPasswordModel([
				'id' => $req->params['id'] ?? null,
				'token' => $req->params['token'] ?? null,
				'password' => $req->body['password']
			]);


			// Validate passwords
			$Password->validate();

			// Verify token
			$Password->token();

			// Redirect to login
			$res->redirect('/login');
		}

	}