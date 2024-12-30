<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');
$data = json_decode(file_get_contents('php://input'), true);

$lid = $data['lid'];
$mid = $data['mid'];
$lvid = $data['lvid'];
$eventType = $data['eventType'];
$timestamp = $data['timestamp'];

$sql = "INSERT INTO lecture_watch (lid, mid, lvid, event_type, timestamp) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("issss", $lid, $mid, $lvid, $eventType, $timestamp);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}