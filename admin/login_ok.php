<?php

include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/header.php');

$userid = $_POST['userid'];
$userpw = $_POST['userpw'];
$password = hash('sha512',$userpw);

$sql = "SELECT * FROM admins WHERE userid='$userid' and passwd = '$password'";
$result = $mysqli->query($sql);
$data = $result ->fetch_object();

if($data){
  $update_sql = "UPDATE admins SET last_login = now() WHERE idx = $data->idx";
  $update_result = $mysqli->query($update_sql);
  $_SESSION['AUIDX'] = $data->idx;
  $_SESSION['AUID'] = $data->userid;
  $_SESSION['AUNAME'] = $data->username;
  $_SESSION['AULEVEL'] = $data->level;

  echo "<script>
    alert('관리자님 반갑습니다.');
    location.href='index.php';
  </script>";

}else{
  echo "<script>
    alert('아이디 또는 비번이 맞지 않습니다.');
    history.back();
  </script>";
}


?>