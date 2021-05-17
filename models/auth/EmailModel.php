<?php


	namespace app\models\auth;


	use app\core\Application;
	use app\core\mvc\Model;

	class EmailModel extends Model
	{

		private $User;

		protected $email;

		/**
		 * EmailModel constructor
		 * @param $data
		 */
		public function __construct($data) {
			// Run parents constructors
			parent::__construct();
			// Get user
			$this->User = Application::$app->user;
			// Load model data
			$this->load($data);
			// Remove email from session
			Application::$app->session->unset('email');
		}

		public function exists() {
			if (
				!$this->User->exists('email', $this->email) ||
				$this->User->is_active('email', $this->email, 'active')
			) {
				Application::$app->response->redirect('/');
			}
		}

		public function token() {
			// Get user data
			$user = $this->User->get_info('email', $this->email);
			// If user has no valid token
			if (
				!$this->User->has_token($this->User->id('email', $this->email), 'e')
			) {
				// Generate new token
				$token = $this->User->token
					->generate()
					->store($user['id'], 'e');
			} else {
				// If user has a valid token
				// Get token
				$token = $this->User->token->get($user['id'], 'e');
			}

			// Send email
			$this->mail($user, $token);
		}

		private function mail($user, $token) {
			Application::$app->mailer->to($this->email)
				->subject('Verify email')
				->view('mails/verify-email', [
					'username' => $user['name'],
					'id' => $user['id'],
					'token' => $token
				])->send();
		}

	}