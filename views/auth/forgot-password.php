<?php

	use app\core\form\Form;

	$form = new Form([
		'attributes' => [
			'method' => 'POST',
			'action' => '/password/forgot',
			'class' => 'auth-form'
		],
		'field_container' => ['<div class="input-gp">', '</div>'],
		'feedback_container' => ['<div class="feedback">', '</div>']
	]);

	$form->begin();

	$form->field([
		'type' => 'email',
		'name' => 'email',
		'label' => 'Email :',
		'attributes' => ['placeholder' => 'example@example.com']
	]);

	echo '<button>Send link</button>';

	$form->end();