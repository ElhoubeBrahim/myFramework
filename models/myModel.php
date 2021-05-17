<?php


	namespace app\models;

	use app\core\mvc\Model;

	class myModel extends Model
	{

		public function test() {
			echo '<pre>';
			print_r($this);
			echo '</pre>';
		}

	}