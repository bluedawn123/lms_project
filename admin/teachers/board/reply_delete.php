<?php
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

$pid = $_GET['pid'];  // 댓글 ID
$b_pid = $_GET['b_pid'];  // 게시물 ID (댓글이 속한 게시물의 PID)
$category = $_GET['category'];

$sql = "DELETE FROM board_reply WHERE pid = $pid";
$result = $mysqli->query($sql);

switch ($category) {
  case 'all':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=$b_pid&category=all"; 
      break;
  case 'qna':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=$b_pid&category=qna";
      break;
  case 'notice':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=$b_pid&category=notice";
      break;
  case 'event':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=$b_pid&category=event";
      break;
  case 'free':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=$b_pid&category=free"; 
      break;
  default:
      die("침몰");
}



if($result){
  echo "<script>
  alert('댓글 삭제 성공');
  location.href='$redirect_url';
  </script>";
}else{
  echo "<script>
  alert('댓글 삭제 실패');
  history.back();
  </script>";
}
?>