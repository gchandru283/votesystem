<?php 
session_start();
include 'includes/header.php';
include 'includes/conn.php';
include 'encryption.php';?>

<?php
if (isset($_POST['register'])) {
    // Debugging: Check if the form data is being received
    var_dump($_POST);
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $captcha = $_POST['captcha'];
    $captchaName = $_POST['captchaName'];
    $dob = $_POST['dob'];
    $mobile = $_POST['mobile'];
    $aadhar = $_POST['aadhar'];
    $voterid = $_POST['voterid'];

    // Calculate age from date of birth
    $dob_date = DateTime::createFromFormat('Y-m-d', $dob);
    if ($dob_date === false) {
        $_SESSION['error'] = "Invalid date format";
        header('location: register.php');
        exit();
    } else {
        // Calculate age from date of birth
        $today = new DateTime();
        $interval = $today->diff($dob_date);
        $age = $interval->y;
    }

    // Check if image file is a valid image
    if (!isset($_FILES["photo"]["tmp_name"])) {
        $_SESSION['error'] = "No file uploaded";
        header('location: register.php');
        exit();
    }

    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check === false) {
        $_SESSION['error'] = 'File is not an image.';
        header('location: register.php');
        exit();
    }

    // Check file size
    $fileSize = $_FILES["photo"]["size"];
    if ($fileSize < 500000 || $fileSize > 3000000) { // between 500KB and 3MB
        $_SESSION['error'] = "File size should be between 500KB and 3MB";
        header('location: register.php');
        exit();
    }

    // Move uploaded file
    $filename = $_FILES['photo']['name'];
    $file_tmp = $_FILES['photo']['tmp_name'];
    $new_filename = $voterid . '_' . $filename;
    if (!move_uploaded_file($file_tmp, 'uploads/' . $new_filename)) {
        $_SESSION['error'] = "Sorry, there was an error uploading your file.";
        header('location: register.php');
        exit();
    }

    // Insert into database
    $encrypted_firstname = encryptData($fname);
    $encrypted_lastname = encryptData($lname);
    $encrypted_dob = encryptData($dob);
    $encrypted_voterid = encryptData($voterid);
    $encrypted_aadhar = encryptData($aadhar);
    $encrypted_mobile = encryptData($mobile);
    $encrypted_age = encryptData($age);

    if ($captchaName != $captcha . '.jpeg') {
        $_SESSION['error'] = "Invalid Captcha";
        header('location: register.php');
        exit();
    }

    $sql = "INSERT INTO REGISTERED (firstname, lastname, dob, age, photo, mobile, aadhar, voterid) 
            VALUES ('$encrypted_firstname', '$encrypted_lastname', '$encrypted_dob', '$encrypted_age', 
            '$new_filename', '$encrypted_mobile', '$encrypted_aadhar', '$encrypted_voterid')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Registration Successful!";
        header('location: register.php');
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
        header('location: register.php');
        exit();
    }
}
header('location: register.php');
// Close connection
$conn->close();
?>
