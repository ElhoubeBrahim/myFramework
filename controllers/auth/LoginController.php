<?php


	namespace app\controllers\auth;
	use app\core\Application;
	use app\core\mvc\Controller;
	use app\models\auth\LoginModel;
	use app\models\oauth\UrlModel;


	class LoginController extends Controller
	{

		public function render($req, $res) {
			// Get url model
			$Url = new UrlModel();
			// Get OAuth urls
			$google = $Url->url('google');
			$facebook = $Url->url('facebook');
			$github = $Url->url('github');
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

			// Get user login model
			$User = new LoginModel([
				'email' => $req->body['email'],
				'password' => $req->body['password']
			]);

			// Validate user data
			$User->validate();

			// Login user
			$User->login($req, $res);

			// Verify account
			$User->verify($req, $res);

			// Remember user login
			$User->remember($req, $res);

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