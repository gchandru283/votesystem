<?php
include 'includes/session.php';
include '../encryption.php';

if (isset($_POST['add'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $mobile = $_POST['mobile'];
    $voterid = $_POST['voterid'];

    if (isset($_FILES["photo"]["tmp_name"])) {
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check === false) {
            $_SESSION['error'] = 'File is not an image.';
            header('location: voters.php');
            exit();
        }
    }

    // Check if image file is a valid image
    if (isset($_FILES["photo"]["tmp_name"])) {
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check === false) {
            $_SESSION['error'] = 'File is not an image.';
            header('location: voters.php');
            exit();
        }
    }

    // Check file size (limit set to 1MB)
    if ($_FILES["photo"]["size"] < 300000) { 
        $_SESSION['error'] = "Sorry, your file is too small. Try uploading greater than 300 KB";
        header('location: voters.php');
        exit();
    }
    // Check file size (limit set to 3MB)
    if ($_FILES["photo"]["size"] > 3000000) { 
        $_SESSION['error'] = "Sorry, your file is too large. Try uploading less than 3 MB";
        header('location: voters.php');
        exit();
    }

    $filename = $_FILES['photo']['name'];
    $file_tmp = $_FILES['photo']['tmp_name'];
    $new_filename = $voterid . '_' . $filename;

    if (move_uploaded_file($file_tmp, '../uploads/' . $new_filename)) {
        // After successful upload, insert data into database
        $encrypted_firstname = encryptData($firstname);
        $encrypted_lastname = encryptData($lastname);
        $encrypted_voterid = encryptData($voterid);
        $encrypted_mobile = encryptData($mobile);

        // Encrypt voter before insertion
        $set = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $voters_key = substr(str_shuffle($set), 0, 15);
        $encrypted_voters_key = encryptData($voters_key);

        $sql = "INSERT INTO voters (firstname, lastname, voterid, voters_key, mobile, photo) 
                        VALUES ('$encrypted_firstname', '$encrypted_lastname', '$encrypted_voterid', '$encrypted_voters_key', '$encrypted_mobile', '$new_filename')";
        if ($conn->query($sql)) {
            $_SESSION['success'] = 'Voter added successfully';
        } else {
            $_SESSION['error'] = $conn->error;
        }
    }
    // Redirect after processing
    header('location: voters.php');
} else {
    $_SESSION['error'] = 'Fill up add form first';
    header('location: voters.php');
}
?>