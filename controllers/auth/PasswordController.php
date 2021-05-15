<?php


	namespace app\controllers\auth;
	use app\core\Application;
	use app\core\mvc\Controller;


	class PasswordController extends Controller
	{

		public function render_forgot($req, $res) {
			$res->set_layout('main');
			$res->render('auth/forgot-password', [
				'title' => 'My Framework | Password Forgotten'
			]);
		}

		public function forgot($req, $res) {
			$req->body = Application::sanitize($req->body);
			$valid = $req->validate([
				'email' => ['required', 'email']
			]);

			if (!$valid) {
				$this->render_forgot($req, $res);
			}

			// Get user class
			$User = Application::$app->user;

			// Get sent email
			$email = $req->body['email'];

			// Check if there is no valid token
			if (
				!$User->has_token($User->id('email', $email), 'p') &&
				$User->exists('email', $email)
			) {
				// Get user data
				$user = $User->get_info('email', $email);
				// Generate new token
				$token = $User->token
					->generate()
					->store($user['id'], 'p');
				// Send email
				$req->mailer->to($email)
					->subject('Reset password')
					->view('mails/reset-password', [
						'username' => $user['name'],
						'id' => $user['id'],
						'token' => $token
					])->send();
			}

			$req->session->flash('success', 'We had been sent a reset link to your email');
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
			// Get id and token
			$id = $req->params['id'] ?? null;
			$token = $req->params['token'] ?? null;

			// Validate passwords
			$valid = $req->validate([
				'password' => ['required', 'min_length:8'],
				'confirm' => ['same:password']
			]);

			// If is not valid
			if (!$valid) {
				// Render reset
				$this->render_reset($req, $res);
				return;
			}

			// Get user class
			$User = Application::$app->user;

			// Verify token
			if ($User->token->verify($id, $token, 'p')) {
				$User->password->update($id, $req->body['password']);
				$User->token->remove($id, $token, 'p');
				$req->session->flash('success', 'Password reset successfully');
			} else {
				$req->session->flash('error', 'We can\'t reset your password');
			}

			// Redirect to login
			$res->redirect('/login');
		}

	}