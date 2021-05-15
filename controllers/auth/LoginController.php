<?php


	namespace app\controllers\auth;
	use app\core\Application;
	use app\core\mvc\Controller;


	class LoginController extends Controller
	{

		public function render($req, $res) {
			// Get OAuth urls
			$google = Application::$app->user->oauth->googleClient->createAuthUrl();
			$facebook = Application::$app->user->oauth->facebookClient->getAuthorizationUrl();
			$github = Application::$app->user->oauth->githubClient['url'];
			// Render login form view
			$res->set_layout('main');
			$res->render('auth/login', [
				'title' => 'My Framework | Login',
				'google' => $google,
				'facebook' => $facebook,
				'github' => $github
			]);
		}

		public function login($req, $res) {
			// Sanitize sent data
			$req->body = Application::sanitize($req->body);

			// Validate user data
			$valid = $req->validate([
				'email' => ['required', 'email', 'max_length:255'],
				'password' => ['required']
			]);

			// If it is invalid
			if (!$valid) {
				// Render the login page with errors
				$this->render($req, $res);
				return;
			}

			// Else
			// Get user class
			$User = Application::$app->user;

			// Authenticate user
			$logged_in = $User->login([
				'email' => $req->body['email'],
				'password' => $req->body['password']
			]);

			// If sent credentials are wrong
			if (!$logged_in) {
				// Render the login page with error
				$req->session->flash('error', 'Wrong credentials');
				$res->redirect('/login');
				return;
			}

			// If account is not verified
			if (
				$User->must_verify &&
				!$User->is_active('email', $req->body['email'], 'active')
			) {
				// Destroy user session
				Application::$app->auth->destroy();
				// Redirect to the verification page
				 $req->session->put('email', $req->body['email']);
				 $res->redirect('/verify/email');
			}

			// Else
			// Set remember me cookie
			if (
				isset($req->body['remember']) &&
				($req->body['remember'] == 1 ||
					$req->body['remember'] == 'on')
			) {
				$id = $User->id('email', $req->body['email']);
				$User->remember($id);
			}
			// Redirect to dashboard
			$res->redirect('/dashboard');
		}

		public function logout($req, $res) {
			// Destroy user session
			$Auth = Application::$app->auth;
			$Auth->destroy();
			// Destroy remember me cookie
			Application::$app->user->forget();
			// Redirect to home
			$res->redirect('/');
		}

	}