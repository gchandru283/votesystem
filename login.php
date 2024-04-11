<?php
session_start();
include 'includes/conn.php';
if (isset ($_POST['login'])) {
	$voter = $_POST['voter'];
	$password = $_POST['password'];
	$captcha = $_POST['captcha'];
	$captchaName = $_POST['captchaName'];
	
	$sql = "SELECT * FROM voters WHERE voters_key = '$voter'";
	$query = $conn->query($sql);

	if ($query->num_rows < 1) {
		$_SESSION['error'] = 'Cannot find voter with the ID';
	} else {
		$row = $query->fetch_assoc();
		if (password_verify($password, $row['password'])) {
			if ($captchaName == $captcha . '.jpeg')
				$_SESSION['voter'] = $row['id'];
			else {
				$_SESSION['error'] = 'Incorrect Captcha';
			}
		} else {
			$_SESSION['error'] = 'Incorrect password';
		}
	}
} else {
	$_SESSION['error'] = 'Input voter credentials first';
}
header('location: index.php');
?>