<?php
include 'includes/session.php';
include '../encryption.php';

if (isset($_POST['edit'])) {
	$id = $_POST['id'];
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$mobile = $_POST['mobile'];
	$voterid = $_POST['voterid'];
	$key = hex2bin('acdb62d64e2029a1873cd28ef52c6bc8c2e1b486400d5c7b40e741f1e28bdf3a');
	
	$encrypted_firstname = encryptData($firstname);
	$encrypted_lastname = encryptData($lastname);
	$encrypted_voterid = encryptData($voterid);
	$encrypted_mobile = encryptData($mobile);

	$sql = "SELECT * FROM voters WHERE id = $id";
	$query = $conn->query($sql);
	$row = $query->fetch_assoc();

	$sql = "UPDATE voters SET firstname = '$encrypted_firstname', lastname = '$encrypted_lastname', mobile = '$encrypted_mobile',voterid = '$encrypted_voterid' WHERE id = '$id'";
	if ($conn->query($sql)) {
		$_SESSION['success'] = 'Voter updated successfully';
	} else {
		$_SESSION['error'] = $conn->error;
	}
} else {
	$_SESSION['error'] = 'Fill up edit form first';
}

header('location: voters.php');

?>