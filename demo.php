<?php
// Define the command to execute the Python script
$path = "images/Photo.jpg";
$command = "python image_rec.py $path";

// Execute the command and capture output
$output = exec($command, $outputArray, $returnValue);

// Initialize a variable to store the output messages
$message = "";
$result = "";

// Check if execution was successful
if ($returnValue === 0) {
    $message .= "Python script executed successfully.\n";
    // Output captured from the Python script
    foreach ($outputArray as $line) {
        $result .= $line . "\n";
    }
} else {
    $message .= "Error executing Python script.\n";
    // Print any error output from the Python script
    foreach ($outputArray as $line) {
        $result .= $line . "\n";
    }
}

// Echo the stored message
// echo $message;
echo $result;

?>