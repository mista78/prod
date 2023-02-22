<?php

function getWidjet($file, $dataComponent)
{
	global $request, $route, $conf;
	extract(json_decode(json_encode($dataComponent), true));
	[$file] = glob(APP . "Component" . DS .  ucfirst($file) . DS .  "*.{php,html}",GLOB_BRACE);
	ob_start();
	if (file_exists($file)) {
		require $file;
	}
	$dataWIdjet = ob_get_clean();
	return trim($dataWIdjet);
}

function Component($component)
{
	return isset($component['component']) ? getWidjet($component['component'], $component) : null;
}
