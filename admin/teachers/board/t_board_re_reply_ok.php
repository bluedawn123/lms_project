<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/dbcon.php');

$r_pid = $_POST['r_pid'];
$pid = $_POST['pid'];
$content = $_POST['content'];
$category=$_POST['category'];


if (isset($_SESSION['AUID'])) {
    // 관리자 로그인 시
    $user_id = $_SESSION['AUID'];
  } else if (isset($_SESSION['TUID'])) {
    // 강사 로그인 시
    $user_id = $_SESSION['TUID'];
  }


$sql="INSERT INTO board_re_reply (r_pid,user_id,content) VALUES ($r_pid ,'$user_id','$content')";


$result = $mysqli->query($sql);


switch ($category) {
	case 'all':
		$redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=$pid&category=all"; 
		break;
	case 'qna':
		$redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=$pid&category=all"; 
		break;
	case 'notice':
		$redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=$pid&category=all";  
		break;
	case 'event':
		$redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=$pid&category=all";  
		break;
	case 'free':
		$redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=$pid&category=all"; 
		$un_redirect_url = '/qc/admin/teachers/teachers_board/t_board_list.php';
		break;
	default:
		die("침몰");
  }
  


if ($result) {
  echo "<script>
      alert('대댓글 작성 완료');
      location.href='$redirect_url';
  </script>";
} else {
  echo "<script>
      alert('대댓글 작성 실패');
      history.back();
  </script>";
}
?>