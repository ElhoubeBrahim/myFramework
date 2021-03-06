<?php


	namespace app\models\auth\password;


	use app\controllers\auth\PasswordController;
	use app\core\Application;
	use app\core\mvc\Model;

	class ResetPasswordModel extends Model
	{
		private $User;

		protected $password;
		protected $id;
		protected $token;

		public function __construct($data) {
			// Run parents constructors
			parent::__construct();
			// Get user
			$this->User = Application::$app->user;
			// Load model data
			$this->load($data);
		}

		public function rules() {
			return [
				'password' => ['required', 'min_length:8'],
				'confirm' => ['same:password']
			];
		}

		public function token() {
			if ($this->User->token->verify($this->id, $this->token, 'p')) {
				$this->User->password->update($this->id, $this->password);
				$this->User->token->remove($this->id, $this->token, 'p');
				Application::$app->session->flash('success', 'Password reset successfully. Try to login');
			} else {
				Application::$app->session->flash('error', 'We can not reset your password. Please try again');
			}
		}

		public function invalid($req, $res) {
			$req->session->flash('error', 'Validation failed', 0);
			$controller = new PasswordController();
			$controller->render_reset($req, $res);
			return;
		}
	}