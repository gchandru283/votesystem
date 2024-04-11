<?php
include 'includes/session.php';

if (isset($_POST['upload'])) {
    $id = $_POST['id'];
    $target_dir = "uploads/";
    $sql = "SELECT voterid FROM voters WHERE id = '$id'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $voterid = $row['voterid'];
        $photo_name = $voterid . '_' . basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $photo_name;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION['error'] = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["photo"]["size"] > 1000000) { // Adjust the size limit as per your requirement
            $_SESSION['error'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $_SESSION['error'] = "Sorry, only JPG, JPEG & PNG files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $_SESSION['error'] = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $sql = "UPDATE voters SET photo = '$photo_name' WHERE id = '$id'";
                if ($conn->query($sql)) {
                    $_SESSION['success'] = 'Photo updated successfully';
                } else {
                    $_SESSION['error'] = $conn->error;
                }
            }
        }
    } else {
        $_SESSION['error'] = 'Voter not found';
    }
} else {
    $_SESSION['error'] = 'Select voter to update photo first';
}

header('location: voters.php');
?>
