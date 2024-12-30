<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/dbcon.php');
$category = $_POST['category'];
$pid = $_POST['pid'];
$b_pid = $_POST['b_pid'];
// echo $pid;
// echo $b_pid;
$content = $_POST['content'];

switch ($category) {
    case 'all':
        $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=".$b_pid."&category=all";
        break;
    case 'qna':
        $redirect_url = '/qc/admin/teachers/teachers_board/t_read.php?pid='.$b_pid.'&category=qna'; 
        break;
    case 'notice':
        $redirect_url = '/qc/admin/teachers/teachers_board/t_read.php?pid='.$b_pid.'&category=notice'; 
        break;
    case 'event':
        $redirect_url = '/qc/admin/teachers/teachers_board/t_read.php?pid='.$b_pid.'&category=event'; 
        break;
    case 'free':
        $redirect_url = '/qc/admin/teachers/teachers_board/t_read.php?pid='.$b_pid.'&category=free'; 
        break;
    default:
        die("유효하지 않은 카테고리입니다.");
}

$sql = "UPDATE board_reply SET content='$content' WHERE pid=$pid";
$result = $mysqli->query($sql);
if($result){
  echo "<script>
    alert('댓글 수정 완료');
    location.href='$redirect_url';
    </script>";
}else{
  echo "<script>
  alert('댓글 수정 실패');
   history.back();
  </script>";
}
?>