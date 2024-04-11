<?php
session_start();
include 'includes/conn.php';
include_once './encryption.php';

if (isset($_POST['login'])) {
    // Debugging: Check if the form data is being received
    var_dump($_POST);

    $voters_key = $_POST['voters_key'];
    $password = $_POST['password'];
    $captcha = $_POST['captcha'];
    $captchaName = $_POST['captchaName'];

    // Fetch all records from the database
    $sql = "SELECT * FROM voters";
    $result = $conn->query($sql);
    
    if ($result === false) {
        // Debugging: Check for SQL errors
        echo "SQL Error: " . $conn->error;
        exit();
    }

    // Loop through each record
    while ($row = $result->fetch_assoc()) {
        // Decrypt the encrypted data from the current record
        $decrypted_voters_key = decryptData($row['voters_key']);
        $decrypted_password = decryptData($row['voterid']);

        // Compare decrypted data with user input
        if ($voters_key == $decrypted_voters_key && $password == $decrypted_password && $captchaName == $captcha . '.jpeg') {
            // If match found, set session and redirect
            echo $_SESSION['decrypted_voters_key'];
            echo $row['id'];
            header('location: index.php');
            exit();
        }
    }

    // If loop completes without successful login, redirect back with error
    $_SESSION['error'] = 'Invalid credentials';
    header('location: index.php');
    exit();
} else {
    // Debugging: Check if the login form is being submitted
    echo "Login form not submitted";
    exit();
}


// session_start();
// include 'includes/conn.php';
// include './encryption.php';

// if (isset($_POST['login'])) {
// 	$voters_key = $_POST['voters_key'];
// 	$password = $_POST['password'];
// 	$captcha = $_POST['captcha'];
// 	$captchaName = $_POST['captchaName'];
// 	$key = hex2bin('acdb62d64e2029a1873cd28ef52c6bc8c2e1b486400d5c7b40e741f1e28bdf3a');

// 	// Fetch all records from the database
// 	$sql = "SELECT * FROM voters";
// 	$result = $conn->query($sql);

// 	// Loop through each record
// 	while ($row = $result->fetch_assoc()) {
// 		// Decrypt the encrypted data from the current record
// 		$decrypted_voters_key = decryptData($row['voters_key'], $key);
// 		$decrypted_password = decryptData($row['voterid'], $key);

// 		// Compare decrypted data with user input
// 		if ($voters_key == $decrypted_voters_key) {
// 			// If match found, proceed with captcha verification
// 			if ($password == $decrypted_password) {

// 				if ($captchaName == $captcha . '.jpeg') {
// 					$_SESSION['decrypted_voters_key'] = $row['id'];
// 				} else {
// 					$_SESSION['error'] = 'Incorrect Captcha';
// 					header('location: index.php');
// 					exit(); // Exit the script due to incorrect captcha
// 				}
// 			} else {
// 				$_SESSION['error'] = 'Incorrect Pssword';
// 				header('location: index.php');
// 				exit(); // Exit the script due to incorrect password
// 			}

// 		} else {
// 			$_SESSION['error'] = 'Cannot find voter with the ID';
// 			header('location: index.php');
// 			exit(); // Exit the script due to incorrect ID
// 		}
// 	}

// } else {
// 	$_SESSION['error'] = 'Input voter credentials first';
// 	header('location: index.php');
// 	exit(); // Exit the script if login form not submitted
// }

// header('location: index.php');
?>