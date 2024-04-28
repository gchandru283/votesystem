<?php
use UltraMsg\WhatsAppApi;

function sendMessages(){
    require_once('vendor/autoload.php');
    include '../encryption.php';
    include '../includes/conn.php';
    
    $ultramsg_token = "po91jk3b9ov9xtjn"; // Your Ultramsg.com token
    $instance_id = "instance84218"; // Your Ultramsg.com instance id

    // Create a new UltraMsg\WhatsAppApi instance
    $client = new WhatsAppApi($ultramsg_token, $instance_id);

    // Function to send a message to a recipient
    function sendMessage($client, $to, $body) {
        $api = $client->sendChatMessage($to, $body);
        return $api;
    }

    $sql = "SELECT * FROM voters";
    $result = $conn->query($sql);
    
    if($result){
        // Loop through each record
        while ($row = $result->fetch_assoc()) {
            $mobile = decryptData($row['mobile']);
            $to = "+91" . $mobile; 
            $body = "Dear User, the e-Voting has been completed. Use the below link to see results! \n\n  www.votesystem.com/results \n\nThank you!"; 
            $api = sendMessage($client, $to, $body);
        }
        return "Messages sent successfully!";
    } else {
        return "Failed to fetch records from the database\n";
    }
}

?>
