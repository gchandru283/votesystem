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


<?php
// Check if the file was uploaded without errors
if ($_FILES["photo"]["error"] == UPLOAD_ERR_OK) {
    $uploadDir = "uploads/"; // Specify the directory where you want to store the images
    $uploadFile = $uploadDir . basename($_FILES["photo"]["name"]);

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $uploadFile)) {
        echo "The file ". basename( $_FILES["photo"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
} else {
    echo "Error: " . $_FILES["photo"]["error"];
}
?>
