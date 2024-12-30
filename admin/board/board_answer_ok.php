<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/dbcon.php');

$pid = $_GET['pid'];
$category = $_GET['category'];


$sql = "SELECT pid,status FROM board WHERE pid=$pid";
$result = $mysqli->query($sql);
$data = $result->fetch_object();


if((int)$data->status === 0 ){
  $update_sql = "UPDATE board SET status = status +1 WHERE pid=$pid";
  $update_result = $mysqli->query($update_sql);
  if($update_result){
    echo "<script>
      alert('답변완료');
      location.href='/qc/admin/board/read.php?pid='+$pid+'&category=qna';
      </script>";
  }else{
    echo "<script>
    alert('답변실패');
     history.back();
    </script>";
  }
};



?>