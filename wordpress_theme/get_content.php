<?php

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

	$html_path = '../../../cache/wordpress_theme_'.$token.'.html';

	if (!file_exists ($html_path))
	{
		echo 'Plik nie istnieje. Odswiez okno w przegladarce.';
		exit;
	}

	echo file_get_contents($html_path);

	unlink($html_path);
?>
