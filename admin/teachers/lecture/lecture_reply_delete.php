<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

$lpid = $_POST['lpid'];

$delete_sql = "DELETE FROM lecture_reply WHERE lpid = $lpid";
$delete_result = $mysqli->query($delete_sql);

if ($delete_result) {
  $r_data = array('result' => 1); //성공
  echo json_encode($r_data);
} else {
  $r_data = array('result' => 0); //실패
  echo json_encode($r_data);
}
