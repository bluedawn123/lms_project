<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/dbcon.php');

$lcid = $_POST['lcid'];
$name = $_POST['name'];

$update_sql = "UPDATE lecture_category SET name = '$name' WHERE lcid = $lcid";
$update_result = $mysqli->query($update_sql);

if ($update_result) {
  $r_data = array('result' => 1); //성공
  echo json_encode($r_data);
} else {
  $r_data = array('result' => 0); //실패
  echo json_encode($r_data);
}
