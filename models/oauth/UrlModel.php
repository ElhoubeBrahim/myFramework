<?php


	namespace app\models\oauth;

	use app\core\mvc\Model;

	class UrlModel extends Model
	{

		private $google;
		private $facebook;
		private $github;

		public function __construct()
		{
			// Run parents constructors
			parent::__construct();
			// Get facebook url
			$this->facebook = (new FacebookModel([]))->url();
			$this->google = (new GoogleModel([]))->url();
			$this->github = (new GithubModel([]))->url();
		}

		public function url($provider) {
			return $this->{$provider} ?? '#';
		}

	}