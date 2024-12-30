<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');
$id = isset($_SESSION['AUID']) ? $_SESSION['AUID']  : $_SESSION['TUID'];
if (!isset($id)) {
  echo "
    <script>
      alert('관리자로 로그인해주세요');
      location.href = '../login.php';
    </script>
  ";
}
$lvid = $_POST['lvid'];
$sql = "SELECT * FROM lecture_video WHERE lvid=$lvid";
$result = $mysqli->query($sql);
$data = $result->fetch_object();

if ($data->t_id !== $id) {
  $return_data = array('result' => 'mine');
  echo json_encode($return_data);
  exit;
}

$del_sql = "DELETE FROM lecture_video WHERE lvid=$lvid";
$del_result = $mysqli->query($del_sql);

if ($del_result) {
  $delete_file = $_SERVER['DOCUMENT_ROOT'] . $data->video_lecture;
  unlink($delete_file);
  $return_data = array('result' => 'ok');
  echo json_encode($return_data);
  exit;
} else {
  $return_data = array('result' => 'error');
  echo json_encode($return_data);
  exit;
}

$mysqli->close();
