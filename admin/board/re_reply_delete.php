<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/dbcon.php');

// GET 데이터 수신
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$pid=$_GET['pid']; //게시물 pid
$reply_id = $_GET['reply_id']; // 댓글의 pid
$re_reply_pid = $_GET['re_reply_pid']; // 대댓글 pid

// echo $category;
// echo $pid;
// echo $reply_id;
// echo $re_reply_pid;

if ($re_reply_pid && $reply_id) {
  // 댓글의 pid와 대댓글의 r_pid가 일치하는 대댓글을 삭제
  $sql = "DELETE FROM board_re_reply WHERE pid = $re_reply_pid AND r_pid = $reply_id";
} 



switch ($category) {
	case 'all':
		$redirect_url = "/qc/admin/board/read.php?pid=$pid&category=all"; 
		break;
	case 'qna':
		$redirect_url = "/qc/admin/board/read.php?pid=$pid&category=qna"; 
		break;
	case 'notice':
		$redirect_url = "/qc/admin/board/read.php?pid=$pid&category=notice";  
		break;
	case 'event':
		$redirect_url = "/qc/admin/board/read.php?pid=$pid&category=event";  
		break;
	case 'free':
		$redirect_url = "/qc/admin/board/read.php?pid=$pid&category=free"; 
		$un_redirect_url = '/qc/admin/board/board_list.php';
		break;
	default:
		die("침몰");
  }
  

	if($mysqli->query($sql)){
		echo "<script>
		alert('대댓글 삭제 성공');
		location.href='$redirect_url';
		</script>";
	}else{
		echo "<script>
		alert('대댓글 삭제 실패');
		location.href='$un_redirect_url';
		</script>";
	}



?>