<?php
session_start();
include 'includes/conn.php';

if (isset ($_POST['login'])) {
	$voter = $_POST['voter'];
	$password = $_POST['password'];
	$captchaName = $_POST['Captcha'];

	$sql = "SELECT * FROM voters WHERE voters_id = '$voter'";
	$query = $conn->query($sql);

	if ($query->num_rows < 1) {
		$_SESSION['error'] = 'Cannot find voter with the ID';
	} else {
		$row = $query->fetch_assoc();
		if (password_verify($password, $row['password'])) {
			$sql1 = "SELECT * FROM captcha WHERE name = '$captchaName'";
			$query1 = $conn->query($sql1);
			$row1 = $query1->fetch_assoc();
			// if (password_verify($captchaName, $row1['password'])) 
				// $_SESSION['voter'] = $row['id'];
			// } else {
			// 	$_SESSION['error'] = 'Incorrect Captcha!';
			// }
		} else {
			$_SESSION['error'] = 'Incorrect password';
		}
	}

} else {
	$_SESSION['error'] = 'Input voter credentials first';
}

header('location: index.php');

?>