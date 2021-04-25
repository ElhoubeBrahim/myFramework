<?php


	namespace app\core\router;

	/**
	 * Class Middleware
	 * @package app\core\router
	 */
	abstract class Middleware
	{
		/**
		 * @param $req
		 * @param $res
		 * @return mixed
		 */
		abstract public function run($req, $res);
	}