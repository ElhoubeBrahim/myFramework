<?php

	use app\core\form\Form;

	$form = new Form([
	    'attributes' => [
			    'method' => 'POST',
                'action' => '/register',
                'class' => 'auth-form'
            ],
        'field_container' => ['<div class="input-gp">', '</div>'],
        'feedback_container' => ['<div class="feedback">', '</div>']
    ]);

	$form->begin();

	$form->field([
	  'type' => 'text',
      'name' => 'name',
      'label' => 'user name',
      'attributes' => ['placeholder' => 'Jhon Doe']
    ]);

	$form->field([
		'type' => 'email',
		'name' => 'email',
		'label' => 'user email',
        'attributes' => ['placeholder' => 'example@example.com']
	]);

	$form->field([
		'type' => 'password',
		'name' => 'password',
		'label' => 'user password',
        'attributes' => ['placeholder' => '* * * * * * *']
	]);

	echo '<button>Create account</button>';

	$form->end();