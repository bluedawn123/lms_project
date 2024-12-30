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

$ids = json_decode(file_get_contents('php://input'), true);




// 여러 개의 게시물을 삭제
foreach ($ids as $id) {
	$sql = "DELETE FROM board WHERE pid = $id";
	$result = $mysqli->query($sql);
	if ($result !== true) {
		// 쿼리 실행 실패 시 '삭제 실패' 메시지 출력 후 종료
		echo '삭제 실패: ' . $mysqli->error;
		exit;
	}
}



?>