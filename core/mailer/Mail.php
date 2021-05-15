<?php


	namespace app\core\mailer;


	use app\core\Application;
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	class Mail
	{
		private $mailer;
		private $smtp;

		private $from;
		private $tmpFrom = null;
		private $to = [];

		private $subject;
		private $body;
		private $attachs = [];

		public function __construct($config) {
			$this->smtp = $config['smtp'] ?? [];
			$this->from = $config['from'] ?? '';
			$this->mailer = new PHPMailer(true);

			$this->init_mailer();
		}

		private function init_mailer() {
			$this->mailer->isSMTP();
			$this->mailer->Host = $this->smtp['host'];
			$this->mailer->SMTPAuth = true;
			$this->mailer->Username = $this->smtp['username'];
			$this->mailer->Password = $this->smtp['password'];
			$this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$this->mailer->Port = $this->smtp['port'];
		}

		public function from($email) {
			$this->tmpFrom = $email;
			return $this;
		}

		private function get_from() {
			$from = $this->from;
			if ($this->tmpFrom == null) {
				return $from;
			}

			$from = $this->tmpFrom;
			$this->tmpFrom = null;
			return $from;
		}

		public function to($email) {
			$this->to = is_string($email) ? [$email] : $email;
			return $this;
		}

		public function subject($subject) {
			$this->subject = $subject;
			return $this;
		}

		public function view($view, $data = []) {
			$path = Application::$app->view->views_path;
			$this->mailer->isHTML(true);
			$this->body = Application::$app->view->get_content("$path/$view.php", $data);
			return $this;
		}

		public function html($html) {
			$this->mailer->isHTML(true);
			$this->body = $html;
			return $this;
		}

		public function text($text) {
			$this->mailer->isHTML(false);
			$this->body = Application::sanitize($text);
			return $this;
		}

		public function attach($file, $as = null) {
			$this->attachs = [$file];
			if ($as != null) $this->attachs[] = $as;
			return $this;
		}

		public function send() {
			try {
				$this->mailer->setFrom($this->get_from());

				foreach ($this->to as $email) {
					$this->mailer->addAddress($email);
				}

				if (count($this->attachs) > 0) {
					if (isset($this->attachs[1]))
						$this->mailer->addAttachment($this->attachs[0], $this->attachs[1]);
					else
						$this->mailer->addAttachment($this->attachs[0]);
				}

				$this->mailer->Subject = $this->subject;
				$this->mailer->Body = $this->body;

				$this->mailer->send();
			} catch(Exception $e) {
				echo '<pre>';
				print_r($e);
				echo '</pre>';
				Application::$app->session->flash('error', 'Ooops! we can\'t send the email');
			}
		}
	}