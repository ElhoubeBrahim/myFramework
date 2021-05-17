<?php


	namespace app\controllers\auth;
	use app\core\Application;
	use app\core\mvc\Controller;
	use app\models\auth\EmailModel;

	class EmailController extends Controller
	{

		public function render($req, $res) {
			// If there is no email to verify
			if (!$req->session->has('email')) {
				$res->redirect('/');
			}

			// Get email and Email Model class
			$email = $req->session->get('email');
			$Email = new EmailModel(['email' => $email]);

			// Check if email exists
			$Email->exists();

			// Check if there is no valid token
			$Email->token();

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