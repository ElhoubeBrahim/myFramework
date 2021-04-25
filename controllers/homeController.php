<?php

	/**
	 * To make a controller copy this file and rename the class
	 * then implement your methods
	 */

	namespace app\controllers;
	use app\core\mvc\Controller;


	class homeController extends Controller
	{

		public function home($req, $res) {
			$res->set_layout('main');
			$res->render('home', [
				'title' => 'My Framework | Home'
			]);
		}

	}