<?php
	$currentPage = basename($_SERVER['PHP_SELF']);
	session_start();

	$con = new mysqli("localhost", "root", "Password1", "questionnaire");
	if ($con->connect_errno) {
		printf("Connect failed: %s\n", $con->connect_error);
		exit();
	};

	$cryptSalt = '$2y$06$PizWslhw9Z9oM9QSPt9zY.g9faOSoUdNLO7RemQrWTMY.NOpr3oTG';
?>
