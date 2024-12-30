<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
// header('application/x-www-form-urlencoded');
// $date = mktime(0, 0, 0, date("m"), 1, date("Y"));
// $prev_month = strtotime("-1 month", $date);
// echo $date("m");

$data = [];
$sql = "SELECT * 
    FROM sales_course
    GROUP BY course_name, month
    ORDER BY month DESC";

$result = $mysqli->query($sql);
while ($row = $result->fetch_object()) {
  $data[] = $row;
}


echo json_encode($data, JSON_UNESCAPED_UNICODE);
