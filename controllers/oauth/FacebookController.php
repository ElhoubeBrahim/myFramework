<?php


	namespace app\controllers\oauth;


	use app\core\Application;
	use app\core\mvc\Controller;
	use app\models\oauth\FacebookModel;

	class FacebookController extends Controller
	{

		public function auth($req, $res) {
			// Sanitize params
			$req->params = Application::sanitize($req->params);

			// If there is no user code
			if (!isset($req->params['code'])) {
				$res->redirect('/login');
			}

			// Get facebook model
			$Facebook = new FacebookModel([
				'code' => $req->params['code']
			]);

			// Auth and get data from facebook
			try {
				$Facebook->auth();
			} catch (\Exception $e) {
				$res->redirect('/login');
			}

			// Redirect to dashboard
			$res->redirect('/dashboard');
		}

	}