<?php
use UltraMsg\WhatsAppApi;

function sendMessages(){
    require_once('vendor/autoload.php');
    include '../encryption.php';
    include '../includes/conn.php';
    
    $ultramsg_token = "o6y3u5q52usfjgwt"; // Your Ultramsg.com token
    $instance_id = "instance83316"; // Your Ultramsg.com instance id

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
            $voters_key = decryptData($row['voters_key']);
            $voterid = decryptData($row['voterid']);
            $hidden_voterid = substr_replace($voterid, '****', 5, 4);
            $to = "+91" . $mobile; 
            $body = "Dear User, this message is for e-Voting , Find your voter credentials below! \n\n 𝗩𝗼𝘁𝗲𝗿 𝗞𝗲𝘆 : " . $voters_key . "\n 𝗣𝗮𝘀𝘀𝘄𝗼𝗿𝗱 : " .  $hidden_voterid . "\n\nUse the following link to cast your vote Online. \nwww.google.com \n\nThank you!"; 
            $api = sendMessage($client, $to, $body);

            return "Messages sent successfully!";
        }
    } else {
        return "Failed to fetch records from the database\n";
    }
}

?>
