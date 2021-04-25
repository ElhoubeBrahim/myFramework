<?php

	/**
	 * This file contains application settings prototype
	 */

	return [
		'app' => [
			'name' => 'My Framework',
			'url' => 'http://localhost',
			'lang' => 'en',
			'uploads' => '/storage/uploads'
		],

		'views' => 'views',

		'database' => [
			'provider' => 'mysql',
			'mysql' => [
				'hostname' => 'localhost',
				'username' => 'root',
				'password' => '',
				'database' => 'my_framework'
			]
		],

		'mailer' => [
			'smtp' => [
				'host' => 'smtp.mailer.com',
				'port' => 576,
				'username' => 'username',
				'password' => 'password'
			]
		]
	];
