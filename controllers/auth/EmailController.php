<?php


	namespace app\controllers\auth;
	use app\core\Application;
	use app\core\mvc\Controller;

	class EmailController extends Controller
	{

		public function render($req, $res) {
			// If there is no email to verify
			if (!$req->session->has('email')) {
				$res->redirect('/');
			}

			// Get email and User class
			$User = Application::$app->user;
			$email = $req->session->get('email');

			// Remove email from session
			$req->session->unset('email');

			// Check if email exists
			if (
				!$User->exists('email', $email) ||
				$User->is_active('email', $email, 'active')
			) {
				$res->redirect('/');
			}

			// Check if there is no valid token
			if (
				!$User->has_token($User->id('email', $email), 'e')
			) {
				// Get user data
				$user = $User->get_info('email', $email);
				// Generate new token
				$token = $User->token
											->generate()
											->store($user['id'], 'e');
				// Send email
				$req->mailer->to($email)
					->subject('Verify email')
					->view('mails/verify-email', [
						'username' => $user['name'],
						'id' => $user['id'],
						'token' => $token
					])->send();
			}

			// Render the view
			$res->set_layout('main');
			$res->render('auth/verify-email', [
				'title' => 'My Framework | Verify Email',
				'email' => $email
			]);
		}

		public function verify($req, $res) {
			// Get data from url
			$req->params = Application::sanitize($req->params);
			$id = $req->params['id'];
			$token = $req->params['token'];
			// Get user class
			$User = Application::$app->user;

			// Verify token
			if ($User->token->verify($id, $token, 'e')) {
				 $User->activate($id, 'active');
				 $User->token->remove($id, $token, 'e');
				$req->session->flash('success', 'Account activated successfully');
			} else {
				$req->session->flash('error', 'We can\'t activate your account');
			}

			// Redirect to login
			$res->redirect('/login');
		}

	}