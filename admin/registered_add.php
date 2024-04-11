<?php
include 'includes/session.php';
include '../encryption.php';

if (isset($_POST['add'])) {
	$id = $_POST['id'];
	$sql_select = "SELECT * FROM registered WHERE id = '$id'";
	$result = $conn->query($sql_select);
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$mobile = $row['mobile'];
		$photo = $row['photo'];
		$voterid = $row['voterid'];

		//generate voters key
		$set = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$voters_key = substr(str_shuffle($set), 0, 15);
		$encrypted_voters_key = encryptData($voters_key);

		$sql_insert = "INSERT INTO voters (voters_key, voterid, firstname, lastname, mobile, photo) VALUES ('$encrypted_voters_key', '$voterid','$firstname', '$lastname', '$mobile', '$photo')";

		$sql = "DELETE FROM `registered` WHERE id = '$id'";
		$conn->query($sql);
	if ($conn->query($sql_insert)) {
		$_SESSION['success'] = 'Pupil added successfully';
	} else {
		$_SESSION['error'] = $conn->error;
	}
}
}
 else {
	$_SESSION['error'] = 'Select item to add first';
}

header('location: registered.php');

?>