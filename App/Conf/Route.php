<?php  
	require_once APP . 'Frame'.DS.'Core'.DS.'Router.php';
	require_once APP . 'Frame'.DS.'Core'.DS.'Models.php';
	require_once APP . 'Dep'.DS.'Connect.php';
	if (file_exists(APP . 'Dep'.DS.'Database.php')) {
		$data = require_once APP . 'Dep'.DS.'Database.php';
	} else {
		$data = null;
	}
	Connect();
