<!DOCTYPE HTML>
<html lang="<?= app\core\Application::$app->lang; ?>">

	<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<title><?= isset($title) ? $title : 'my Framework'; ?></title>

        <link href="/vendor/remixicon/remixicon.css" rel="stylesheet">
        <link href="/css/style.css" rel="stylesheet">
	</head>

	<body>
        <?php if (isset($flash['success'])) : ?>
          <div class="alert alert-success" onclick="this.remove()">
	          <?= $flash['success'] ?>
          </div>
        <?php endif; ?>

        <?php if (isset($flash['error'])) : ?>
            <div class="alert alert-danger" onclick="this.remove()">
			        <?= $flash['error'] ?>
            </div>
        <?php endif; ?>

        <header class="page-header">
            <div class="brand">
                <a href="/">
                    <img src="/media/images/logo.svg" alt="myFramework" height="40" width="40">
                </a>
            </div>
            <?php if (\app\core\Application::$app->auth->guest()): ?>
                <ul class="nav">
                    <li class="login"><a href="/login">Login</a></li>
                    <li class="register"><a href="/register">Register</a></li>
                </ul>
            <?php else: ?>
                <ul class="nav">
                    <li class="profile"><a href="/profile">Profile</a></li>
                    <li class="logout"><a href="/logout">Logout</a></li>
                </ul>
            <?php endif; ?>
        </header>

		{{ @content }}

        <script src="/js/script.js"></script>
	</body>

</html>