<!DOCTYPE HTML5>
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
	          <?= $flash['success']['value'] ?>
          </div>
        <?php endif; ?>

        <?php if (isset($flash['danger'])) : ?>
            <div class="alert alert-danger" onclick="this.remove()">
			        <?= $flash['danger']['value'] ?>
            </div>
        <?php endif; ?>

		{{ @content }}

        <script src="/js/script.js"></script>
	</body>

</html>