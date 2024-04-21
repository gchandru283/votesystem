<?php
session_start();
include 'includes/conn.php';
include './encryption.php';

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
        if (
            $voters_key == $decrypted_voters_key && $password == $decrypted_password
            && $captchaName == $captcha . '.jpeg'
        ) {
            // If match found, set session and redirect
            
            $_SESSION['voter'] = $row['id'];
        }
    }
    // If loop completes without successful login, redirect back with error
    $_SESSION['error'] = 'Invalid credentials';

} else {
    echo "Login form not submitted";
}

header('location: index.php');

?>