<?php
	session_start();

	if (!isset($_GET['token']))
	{
		echo 'Invalid token';
		exit;
	}

	$token = $_GET['token'];

	if(!preg_match('/[0-9a-z]{48}/', $token))
	{
		echo 'Invalid token';
		exit;
	}


	if(!isset($_SESSION['wordpress_theme']['token'])) {
		echo 'Token not found in session. Refresh your web browser.';
		exit;
	}

	if(strcmp($token, $_SESSION['wordpress_theme']['token']) != 0)
	{
		echo 'Token mismatch. Refresh your web browser.';
	}

	echo $_SESSION['wordpress_theme']['content'];

	unset($_SESSION['wordpress_theme']['token']);
	unset($_SESSION['wordpress_theme']['content']);
?>
