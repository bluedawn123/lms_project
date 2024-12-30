<?php
session_start();
// include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/header.php');
$mysqli = require __DIR__ . "/database.php";

$email = $_POST['email'];
$userpw = $_POST['password'];
$password = hash('sha512',$userpw);
$lastLoginAt = $_POST['lastLoginAt'] ?? '';

$sql = "SELECT * FROM memberskakao WHERE memEmail='$email' and mempassword = '$password'";
$result = $mysqli->query($sql);
$data = $result ->fetch_object();

//로그인 데이터가 있고, 인증을 통해 토근을 null 로 변경한 경우만.
//원래는 verified를 0,1로 하려 그랬는데 이게 더 편한거 같아서 이거로. 카톡도 당연히 됌
if ($data && $data->account_activation_token === null) {  
  // 마지막 로그인 시간 업데이트
  $update_sql = "UPDATE membersKakao 
                   SET lastLoginAt = NOW(), login_count = login_count + 1 
                   WHERE memEmail = ?";
  $update_stmt = $mysqli->prepare($update_sql);
  $update_stmt->bind_param("s", $data->memEmail);
  $update_stmt->execute();

  // 세션 설정
  $_SESSION['MemEmail'] = $data->memEmail;
  $_SESSION['MemId'] = $data->memId;
  $_SESSION['MUNAME'] = $data->memName;
  $_SESSION['Mgrade'] = $data->grade;

  // $_SESSION['AUIDX'] = $data->idx;
  // $_SESSION['AUID'] = $data->userid;
  // $_SESSION['AUNAME'] = $data->username;
  // $_SESSION['AULEVEL'] = $data->level;


  echo "<script>
      alert('로그인 되었습니다.');
      location.href='/qc/index.php';
  </script>";
} else {
  echo "<script>
      alert('아이디 또는 비번이 맞지 않거나 인증이 되지 않았습니다.');
      location.href='loginTest2.php';
  </script>";
}
?>