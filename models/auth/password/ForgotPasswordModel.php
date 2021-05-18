<?php


	namespace app\models\auth\password;

	use app\core\Application;
	use app\core\mvc\Model;
	use app\controllers\auth\PasswordController;

	class ForgotPasswordModel extends Model
	{

		private $User;

		protected $email;

		public function __construct($data) {
			// Run parents constructors
			parent::__construct();
			// Get user
			$this->User = Application::$app->user;
			// Load model data
			$this->load($data);
		}

		public function rules() {
			return ['email' => ['required', 'email']];
		}

		public function token() {
			// Get user data
			$user = $this->User->get_info('email', $this->email);

			// If user exists
			if ($this->User->exists('email', $this->email)) {
				// If user has no valid reset token
				if (
					!$this->User->has_token($this->User->id('email', $this->email), 'p')
				) {
					// Generate new token
					$token = $this->User->token
						->generate()
						->store($user['id'], 'p');
				} else {
					// If user has a valid token
					// Get token
					$token = $this->User->token->get($user['id'], 'e');
				}

				// Send email
				$this->mail($user, $token);
			}
		}

		private function mail($user, $token) {
			Application::$app->mailer->to($this->email)
				->subject('Reset password')
				->view('mails/reset-password', [
					'username' => $user['name'],
					'id' => $user['id'],
					'token' => $token
				])->send();
		}

		public function invalid($req, $res) {
			$req->session->flash('error', 'Validation failed', 0);
			$controller = new PasswordController();
			$controller->render_forgot($req, $res);
			return;
		}

	}