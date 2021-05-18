<?php


	namespace app\controllers\oauth;


	use app\core\Application;
	use app\core\mvc\Controller;
	use app\models\oauth\GoogleModel;

	class GoogleController extends Controller
	{

		public function auth($req, $res) {
			// Sanitize params
			$req->params = Application::sanitize($req->params);

			// If there is no user code
			if (!isset($req->params['code'])) {
				$res->redirect('/login');
			}

			// Get google model
			$Google = new GoogleModel(['code' => $req->params['code']]);

			// Authenticate user
			try {
				$Google->auth();
			} catch (\Exception $e) {
				$res->redirect('/login');
			}

			// Redirect to dashboard
			$res->redirect('/profile');
		}

	}