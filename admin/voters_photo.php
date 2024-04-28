<?php
include 'includes/session.php';
include '../encryption.php';

if (isset($_POST['upload'])) {
    $id = $_POST['id'];

    // Fetching voter ID from the voters table
    $sql_select = "SELECT voterid FROM voters WHERE id = '$id'";
    $result = $conn->query($sql_select);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $encrypted_voterid = $row['voterid'];
        $voterid = decryptData($encrypted_voterid);
    } else {
        $_SESSION['error'] = 'Voter not found';
        header('location: voters.php');
        exit(); 
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
    move_uploaded_file($file_tmp, '../uploads/' . $new_filename);

    $sql = "UPDATE voters SET photo = '$new_filename' WHERE id = '$id'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Photo updated successfully';
    } else {
        $_SESSION['error'] = $conn->error;
    }
} else {
    $_SESSION['error'] = 'Select a Voter to update the photo first';
}

header('location: voters.php');
?>
