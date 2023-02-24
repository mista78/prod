<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Cegos ">
		<meta name="theme-color" content="#317EFB" />
		<title>Kmaoulida <?= (" - " . $conf['block.title']) ?? ''; ?></title>
		<script src="/node_modules/react/umd/react.production.min.js"></script>
		<script src="/node_modules/react-dom/umd/react-dom.production.min.js"></script>
		<script src="/node_modules/@babel/standalone/babel.min.js"></script>

		<?= CompileCss($view) ?>
		<?= $conf['block.content'] ?? ''; ?>
	</head>

	<body>
		<?= getWidjet("moodboard", $view); ?>
		<?= CompileJs($view) ?>
	</body>

</html>