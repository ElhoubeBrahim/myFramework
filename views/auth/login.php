<?php

	use app\core\form\Form;

	$form = new Form([
		'attributes' => [
			'method' => 'POST',
			'action' => '/login',
			'class' => 'auth-form'
		],
		'field_container' => ['<div class="input-gp">', '</div>'],
		'feedback_container' => ['<div class="feedback">', '</div>']
	]);

	$form->begin();
?>

    <div class="title">Login to account</div>

    <div class="oauth">
        <a href="<?= isset($google) ? $google : '#' ?>" class="icon google">
            <i class="ri-google-line"></i>
        </a>
        <a href="<?= isset($facebook) ? $facebook : '#' ?>" class="icon facebook">
            <i class="ri-facebook-line"></i>
        </a>
        <a href="<?= isset($github) ? $github : '#' ?>" class="icon github">
            <i class="ri-github-line"></i>
        </a>
    </div>

<?php

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

?>

	<div class="actions">
        <div class="remember">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember me</label>
        </div>

        <div class="forgot">
            <a href="/password/forgot">
                Password forgotten ?
            </a>
        </div>
    </div>

<?php
	echo '<button>Login</button>';

	$form->end();

