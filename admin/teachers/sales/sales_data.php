<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
// header('application/x-www-form-urlencoded');
// $date = mktime(0, 0, 0, date("m"), 1, date("Y"));
// $prev_month = strtotime("-1 month", $date);
// echo $date("m");

$month = date("n");
for ($i = 2; $i < 8; $i++) {
  $monthArr[] = date("n", strtotime("-{$i} months", $month));
}

$data = [];

foreach ($monthArr as $month) {
  $sql = "SELECT month, sales FROM sales_monthly WHERE month = '{$month}월' ";
  $result = $mysqli->query($sql);
  while ($row = $result->fetch_object()) {
    array_push($data, $row);
  }
}


echo json_encode($data, JSON_UNESCAPED_UNICODE);
