<?php
use UltraMsg\WhatsAppApi;

function sendMessages($number,$otp){
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

    $to = "+91" . $number; 
    $body = "Your OTP for Admin login is ". $otp . ". Don't share it to anyone."; 
    $api = sendMessage($client, $to, $body);

    return "OTP sent successfully!";
}
    
?>
