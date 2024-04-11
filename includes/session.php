<?php
echo "Starting session...";
session_start();
include 'includes/conn.php';
include '../encryption.php';

if (isset($_SESSION['decrypted_voters_key'])) {
	$decrypted_session_voters_key = $_SESSION['decrypted_voters_key'];
	$key = hex2bin('acdb62d64e2029a1873cd28ef52c6bc8c2e1b486400d5c7b40e741f1e28bdf3a');

	// Fetch all records from the database
	$sql = "SELECT * FROM voters";
	$result = $conn->query($sql);

	// Flag to indicate whether a matching record is found
	$match_found = false;

	// Loop through each record
	while ($row = $result->fetch_assoc()) {
		// Decrypt the voters key from the current record
		$decrypted_db_voters_key = decryptData($row['votersKey'], $key);

		// Check if decrypted voters key matches the one from the session
		if ($decrypted_db_voters_key === $decrypted_session_voters_key) {
			// Matching record found, assign to $voter and set flag
			$decrypted_voters_key = $row;
			$match_found = true;
			break;
		}
	}

	// If no matching record found, redirect to index.php
	if (!$match_found) {
		header('location: index.php');
		exit();
	}
} else {
	header('location: index.php');
	exit();
}
?>