<?php

	use app\core\form\Form;

	$form = new Form([
		'attributes' => [
			'method' => 'POST',
			'action' => '',
			'class' => 'auth-form'
		],
		'field_container' => ['<div class="input-gp">', '</div>'],
		'feedback_container' => ['<div class="feedback">', '</div>']
	]);

	$form->begin();

	echo '<div class="title">Enter new password</div>';

	$form->field([
		'type' => 'password',
		'name' => 'password',
		'label' => 'Password :',
		'attributes' => ['placeholder' => '* * * * * * * *']
	]);

	$form->field([
		'type' => 'password',
		'name' => 'confirm',
		'label' => 'Confirm password :',
		'attributes' => ['placeholder' => '* * * * * * * *']
	]);

	echo '<button>Reset password</button>';

	$form->end();