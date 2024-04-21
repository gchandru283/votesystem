<?php
include 'includes/session.php';
include 'includes/conn.php';

// Create an array to hold the response data
$response = array();

// Check if the user is logged in
if (!isset($_SESSION['voter'])) {
    // If not logged in, redirect to the login page
    $response['redirect'] = 'index.php';
} else {
    if (isset($_POST['verify'])) {
        $id = $_POST['id'];
        $sql = "SELECT * FROM voters WHERE id = '$id'";
        $result = $conn->query($sql); 

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $path = 'uploads/' . $row['photo'];
            $path1 = 'images/Photo.jpg';
            $command = "python image_rec.py $path";

            $outputArray = array();
            // Execute the command and capture output
            exec($command, $outputArray, $returnValue);

            // Initialize a variable to store the output messages
            $message = "";
            $resultValue = ""; 

            // Check if execution was successful
            if ($returnValue === 0) {
                $message .= "Python script executed successfully.\n";
                // Output captured from the Python script
                foreach ($outputArray as $line) {
                    $resultValue .= $line . "\n";
                }
                // Check the result and set session message accordingly
                if (trim($resultValue) === "True") {
                    $_SESSION['success'] = 'Face verified successfully.';
                    $response['result'] = true;
                } else {
                    $_SESSION['error'] = ['Face does not match. Please try from a well-lit place.'];
                    $response['result'] = false;
                }
            } else {
                $message .= "Error executing Python script.\n";
                // Print any error output from the Python script
                foreach ($outputArray as $line) {
                    $message .= $line . "\n";
                }
                $_SESSION['error'] = $message; // Set error message if Python script fails
            }
        } else {
            $_SESSION['error'] = 'No voter found with the provided ID.';
        }
    }

    // Set redirect URL
    $response['redirect'] = 'home.php';
}

// Return JSON response
echo json_encode($response);
exit();
?>
