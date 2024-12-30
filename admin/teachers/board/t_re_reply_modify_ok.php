<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/dbcon.php');

$content = $_POST['content'];
$b_pid = $_POST['b_pid']; //댓글 고유번호
$r_pid = $_POST['r_pid']; // 댓글고유번호와 같은 대댓글 번호
$pid = $_POST['pid']; // 대댓글 고유번호
$category = $_POST['category'];
$board_pid = $_POST['board_pid'];

$sql = "UPDATE board_re_reply SET content='$content' WHERE r_pid=$b_pid AND pid=$pid";
$result = $mysqli->query($sql);


switch ($category) {
  case 'all':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=".$board_pid."&category=all";
      break;
  case 'qna':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=".$board_pid."&category=qna"; 
      break;
  case 'notice':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=".$board_pid."&category=notice"; 
      break;
  case 'event':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=".$board_pid."&category=event"; 
      break;
  case 'free':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=".$board_pid."&category=free"; 
      break;
  default:
      die("유효하지 않은 카테고리입니다.");
}

if($result){
  echo "<script>
    alert('대댓글 수정 완료');
    location.href='$redirect_url';
    </script>";
}else{
  echo "<script>
  alert('대댓글 수정 실패');
   history.back();
  </script>";
}
?>