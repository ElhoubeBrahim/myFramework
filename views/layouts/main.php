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
        <?php if (isset($flash)) : ?>
          <pre>
              <?php print_r($flash); ?>
          </pre>
        <?php endif; ?>
		{{ @content }}

        <script src="/js/script.js"></script>
	</body>

</html>