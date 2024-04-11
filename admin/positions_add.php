<?php
	include 'includes/session.php';
	include '../encryption.php';

	if(isset($_POST['add'])){
		$description = $_POST['description'];
		$max_vote = $_POST['max_vote'];
				
		$encrypted_description = encryptData($description);
		$encrypted_max_vote = encryptData($max_vote);

		$sql = "SELECT * FROM positions ORDER BY priority DESC LIMIT 1";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		$priority = $row['priority'] + 1;

		$sql = "INSERT INTO positions (description, max_vote,priority) VALUES ('$encrypted_description', '$encrypted_max_vote', '$priority')";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Position added successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
	else{
		$_SESSION['error'] = 'Fill up add form first';
	}

	header('location: positions.php');
?>