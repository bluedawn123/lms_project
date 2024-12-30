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
if (!isset($user_id)) {
  echo "<script>
      alert('로그인 후 추천 가능합니다.');
      history.back();
  </script>";
  exit;
}



$pid = $_GET['pid'];
$category=$_GET['category'];

//같은 유저가 같은 글 추천 중복 방지
$check_sql = "SELECT COUNT(*) AS count FROM board_like WHERE l_pid = $pid AND user_id = '$user_id'";
$check_result = $mysqli->query($check_sql);
$check_data = $check_result->fetch_object();

// 만약 추천하지 않았다면
if ($check_data->count == 0) {
  // 추천 추가
  $insert_sql = "INSERT INTO board_like (l_pid, user_id) VALUES ($pid, '$user_id')";
  if ($mysqli->query($insert_sql)) {
      // 게시글 추천수 증가
      $update_sql = "UPDATE board SET likes = likes + 1 WHERE pid = $pid";
      $mysqli->query($update_sql);

      echo "<script>
              alert('추천이 반영되었습니다.');
              location.href='t_read.php?pid=$pid&category=$category';  // 해당 카테고리 목록으로 리디렉션
            </script>";
  }
} else {
  echo "<script>
          alert('이미 추천한 게시글입니다.');
          history.back();
        </script>";
}

?>