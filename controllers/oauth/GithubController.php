<?php


	namespace app\controllers\oauth;


	use app\core\Application;
	use app\core\mvc\Controller;
	use app\models\oauth\GithubModel;

	class GithubController extends Controller
	{

		public function auth($req, $res) {
			// Sanitize params
			$req->params = Application::sanitize($req->params);

			// If there is no user code
			if (!isset($req->params['code'])) {
				$res->redirect('/login');
			}

			// Get github model
			$Github = new GithubModel(['code' => $req->params['code']]);

			// Authenticate user
			try {
				$Github->auth();
			} catch (\Exception $e) {
				$res->redirect('/login');
			}

			// Redirect to dashboard
			$res->redirect('/profile');
		}

	}