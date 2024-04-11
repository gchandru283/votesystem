<?php
    session_start();
    include 'includes/conn.php';
    include '../message/message_otp.php';

    // Check if the request is made via AJAX
    if(isset($_POST['username'])){
		$username = $_POST['username'];
		$otpGenerated = $_POST['otpGenerated'];
		$sql = "SELECT * FROM admin WHERE username = '$username'";
		$query = $conn->query($sql);
	
		if($query->num_rows > 0) {
			$row = $query->fetch_assoc();
			$mobile = $row['mobile'];
			$otp_code = sendMessages($mobile, $otpGenerated);
			header('location: index.php');
			exit();
	
		} else {
			$_SESSION['error'] = 'Username not found';
	
		}
	} else {
		// Redirect if accessed directly
		$_SESSION['error'] = 'Input admin credentials first';
	}
?>
