<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/teachers/inc/header.php');

$id = isset($_SESSION['TUID']) ? $_SESSION['TUID'] : null;
if (!isset($id)) {
  echo "
    <script>
      alert('강사로 로그인해주세요');
      location.href = '../login.php';
    </script>
  ";
}

$pid = $_POST['pid'];
$user_id = $_POST['user_id'] ?? '';
$content = $_POST['content'];
$category=$_POST['category'];

if (isset($_SESSION['AUID'])) {
    // 관리자 로그인 시
    $user_id = $_SESSION['AUID'];
  } else if (isset($_SESSION['TUID'])) {
    // 강사 로그인 시
    $user_id = $_SESSION['TUID'];
  }

$sql="INSERT INTO board_reply (b_pid,user_id,content) VALUES ($pid,'$user_id','$content')";


$result = $mysqli->query($sql);


switch ($category) {
  case 'qna':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=$pid&category=qna";
      break;
  case 'notice':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=$pid&category=notice";
      break;
  case 'event':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=$pid&category=event";
      break;
  case 'free':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=$pid&category=free";
      break;
  default:
      die("카테고리를 선택 해주세요.");
}


if ($result) {
  echo "<script>
      alert('댓글 작성 완료');
      location.href='$redirect_url';
  </script>";
} else {
  echo "<script>
      alert('댓글 작성 실패');
      history.back();
  </script>";
}
?>