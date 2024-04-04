<?php
include 'includes/session.php';

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
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		
		//generate voters key
		$set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$voter = substr(str_shuffle($set), 0, 15);

		$sql_insert = "INSERT INTO voters (voters_key, voterid, password, firstname, lastname, mobile, photo) VALUES ('$voter', '$voterid', '$password', '$firstname', '$lastname', '$mobile', '$photo')";

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