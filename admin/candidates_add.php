<?php
include 'includes/session.php';
include '../encryption.php';

if (isset($_POST['add'])) {
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$position = $_POST['position'];
	$platform = $_POST['platform'];
	$voterid = $_POST['voterid'];
	$aadhar = $_POST['aadhar'];
	$mobile = $_POST['mobile'];

	if (isset($_FILES["photo"]["tmp_name"])) {
		$check = getimagesize($_FILES["photo"]["tmp_name"]);
		if ($check === false) {
			$_SESSION['error'] = 'File is not an image.';
			header('location: voters.php');
			exit();
		}
	}

	// Check if image file is a valid image
	if (isset($_FILES["photo"]["tmp_name"])) {
		$check = getimagesize($_FILES["photo"]["tmp_name"]);
		if ($check === false) {
			$_SESSION['error'] = 'File is not an image.';
			header('location: voters.php');
			exit();
		}
	}

	// Check file size (limit set to 1MB)
	if ($_FILES["photo"]["size"] > 1000000) {
		$_SESSION['error'] = "Sorry, your file is too large. Try uploading less than 1 MB";
		header('location: voters.php');
		exit();
	}

	$filename = $_FILES['photo']['name'];
	$file_tmp = $_FILES['photo']['tmp_name'];
	$new_filename = $voterid . '_' . $filename;
	if (move_uploaded_file($file_tmp, '../images/' . $new_filename)) {
		$encrypted_firstname = encryptData($firstname);
		$encrypted_lastname = encryptData($lastname);
		$encrypted_position = encryptData($position);
		$encrypted_platform = encryptData($platform);
		$encrypted_voterid = encryptData($voterid);
		$encrypted_aadhar = encryptData($aadhar);
		$encrypted_mobile = encryptData($mobile);

		$sql = "INSERT INTO candidates (position_id, firstname, lastname, photo, platform, voterid,aadhar,mobile) VALUES ('$encrypted_position', '$encrypted_firstname', '$encrypted_lastname', '$new_filename', '$encrypted_platform','$encrypted_voterid', '$encrypted_aadhar','$encrypted_mobile')";
		// $sql = "INSERT INTO candidates (position_id, firstname, lastname, photo, platform, voterid) VALUES ('$position', '$firstname', '$lastname', '$new_filename', '$platform','$voterid')";
		if ($conn->query($sql)) {
			$_SESSION['success'] = 'Candidate added successfully';
		} else {
			$_SESSION['error'] = $conn->error;
		}
	}

} else {
	$_SESSION['error'] = 'Fill up add form first';
}

header('location: candidates.php');
?>