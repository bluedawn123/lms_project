<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/common.php');

if (!isset($_SESSION['MemEmail'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href = '/qc/loginTest2.php';</script>";
    exit;
}


// 세션 데이터 가져오기
$userEmail = $_SESSION['MemEmail'];
$memId = $_SESSION['MemId'];

// POST 데이터 수집 및 검증
$memName = trim($_POST['memName']);
$birth = trim($_POST['birth']);
$memAddr = trim($_POST['memAddr']);
$number = trim($_POST['number']);
$cover_image = $_FILES['cover_image'] ?? '';

// 파일 업로드 처리
if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
    $fileUploadResult = fileUpload($_FILES['cover_image'], 'image');
    if ($fileUploadResult) {
      $cover_image = $fileUploadResult; // 업로드된 파일 경로를 변수에 저장
    } else {
      echo "<script>
                alert('파일 첨부할 수 없습니다.');
                history.back();
            </script>";
      exit;
    }
  }

// SQL 쿼리 준비
$sql = "UPDATE membersKakao 
        SET memName = ?, birth = ?, memAddr = ?, number = ?, memProfilePath = ?
        WHERE memId = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sssssi", $memName, $birth, $memAddr, $number, $cover_image, $memId);

if ($stmt->execute()) {
    echo "<script>alert('수정이 완료되었습니다.'); location.href = 'http://{$_SERVER['HTTP_HOST']}/qc/users/users_view.php?MemId=" . htmlspecialchars($_SESSION['MemId']) . "';</script>";
} else {
    echo "<script>alert('수정에 실패하였습니다.'); history.back();</script>";
}

$stmt->close();
$mysqli->close();
?>
