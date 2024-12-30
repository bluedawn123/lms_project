<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');


$lids = $_POST['lid'];


$sql = "DELETE FROM lecture_cart WHERE lid IN ($lids)";
$result = $mysqli->query($sql);

if ($result) {
  $response = [
    'status' => 'success',
    'message' => 'Payment processed successfully.',
  ];
} else {
  $response = [
    'status' => 'fail',
    'message' => 'no-result',
  ];
}

echo json_encode($response);

$mysqli->close();
