<?php
include 'includes/conn.php';

if (isset ($_POST['validation'])) {
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$dob = $_POST['dob'];
	$mobile = $_POST['mobile'];
	$aadhar = $_POST['aadhar'];
	$voterid = $_POST['voterid$voterid'];
	$dob_timestamp = strtotime($dob);
    $current_timestamp = time();
    $age = floor(($current_timestamp - $dob_timestamp) / 31556926);
	$sql = "INSERT INTO registered (fname, lname, age, mobile, aadhar, voterid) VALUES('$fname', '$lname', '$age', '$mobile', '$aadhar', '$voterid')";
	$query = $conn->query($sql);

	// if ($query->num_rows < 1) {
	// 	$_SESSION['error'] = 'Cannot find voter with the ID';
	// } else {
	// 		$_SESSION['error'] = 'Incorrect password';
	// 	}
	
	if ($query) {
        $_SESSION['error'] = 'Success!';
    } else {
        $_SESSION['error'] = 'Error: ' . $conn->error;
    }
	}

 else {
	$_SESSION['error'] = 'Enter the correct details!';
}

header('location: register.php');

?>