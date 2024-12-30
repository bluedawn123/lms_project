<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

$id = isset($_SESSION['AUID']) ? $_SESSION['AUID']  : $_SESSION['TUID'];

if (isset($_SESSION['AUIDX'])) {
  $sql = "SELECT * FROM admins WHERE idx = {$_SESSION['AUIDX']}";  //지금 접속한 사람의 id값
} else {
  $sql = "SELECT * FROM teachers WHERE tid = {$_SESSION['TUIDX']}";  //지금 접속한 사람의 id값
}
$result = $mysqli->query($sql);
$data = $result->fetch_object();


$lrid = $_POST['lrid'];
$comment = $_POST['comment'];

$reply_sql = "INSERT INTO lecture_reply (lrid, t_id, comment) 
VALUES 
($lrid, '$id', '$comment')";
$reply_result = $mysqli->query($reply_sql);

if ($reply_result) {
  $r_data = array('result' => 1); //성공
  echo json_encode($r_data);
} else {
  $r_data = array('result' => 0); //실패
  echo json_encode($r_data);
}
