<?php
spl_autoload_register(function ($class) {
	$file = str_replace(['\\', 'Leaf', 'Helpers'], ['/', '', ''], $class);

	if (!file_exists("../src/$file.php")) {
		return;
	} else {
		require "../src/$file.php";
	}
});