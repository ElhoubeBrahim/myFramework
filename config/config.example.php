<?php

	/**
	 * This file contains application settings prototype
	 * Please rename this file to 'config.php'
	 * and start setting your environment
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
			'from' => 'example@example.com',
			'smtp' => [
				'host' => 'smtp.mailer.com',
				'port' => 576,
				'username' => 'username',
				'password' => 'password'
			]
		],

		'auth' => [
			'table' => 'users',
			'verification' => false,
			'oauth' => [
				'google' => [
					'id' => '1234',
					'secret' => '1234',
					'redirect' => '/oauth/google'
				],
				'facebook' => [
					'id' => '1234',
					'secret' => '1234',
					'redirect' => '/oauth/facebook'
				],
				'github' => [
					'name' => 'My Framework',
					'id' => '1234',
					'secret' => '1234',
					'redirect' => '/oauth/github'
				]
			]
		]
	];
