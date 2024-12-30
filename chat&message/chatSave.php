<?php
$sender_id = $_POST['sender_id'];
$receiver_id = $_POST['receiver_id'];
$message = $_POST['message'];

$conn = new mysqli('localhost', 'quantumcode', '12345', 'quantumcode');
$sql = "INSERT INTO chats (sender_id, receiver_id, message) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);
$stmt->execute();
$conn->close();