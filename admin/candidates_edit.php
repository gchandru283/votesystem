<?php
	include 'includes/session.php';
	include '../encryption.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$position = $_POST['position'];
		$voterid = $_POST['voterid'];
		$aadhar = $_POST['aadhar'];
		$mobile = $_POST['mobile'];
		$platform = $_POST['platform'];

		$encrypted_firstname = encryptData($firstname);
		$encrypted_lastname = encryptData($lastname);
		$encrypted_position = encryptData($position);
		$encrypted_platform = encryptData($platform);
		$encrypted_voterid = encryptData($voterid);
		$encrypted_aadhar = encryptData($aadhar);
		$encrypted_mobile = encryptData($mobile);

		$sql = "UPDATE candidates SET firstname = '$encrypted_firstname', lastname = '$encrypted_lastname', position_id = '$encrypted_position', platform = '$encrypted_platform', voterid = '$encrypted_voterid', mobile = '$encrypted_mobile', aadhar = '$encrypted_aadhar' WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Candidate updated successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Fill up edit form first';
	}

	header('location: candidates.php');

?>