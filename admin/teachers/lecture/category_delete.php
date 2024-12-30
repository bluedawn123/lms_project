<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/dbcon.php');

$lcid = $_POST['lcid'];

$delete_sql = "DELETE FROM lecture_category WHERE lcid = $lcid";
$delete_result = $mysqli->query($delete_sql);

if ($delete_result) {
  $r_data = array('result' => 1); //성공
  echo json_encode($r_data);
} else {
  $r_data = array('result' => 0); //실패
  echo json_encode($r_data);
}
