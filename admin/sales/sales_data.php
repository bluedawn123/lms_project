<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');


$data = [];

$sql = "SELECT 
  DATE_FORMAT(createdate, '%c월') AS month,
  SUM(total_price) AS sales
  FROM lecture_order
  GROUP BY DATE_FORMAT(createdate, '%c월')
  ORDER BY MONTH(createdate) DESC LIMIT 6
";

$result = $mysqli->query($sql);

if ($result) {
    while ($row = $result->fetch_object()) {
      array_push($data, $row);
    }
} else {
    die("Query failed: " . $mysqli->error);
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
