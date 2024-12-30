<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/common.php');

$name = $_POST['name'] ?? '';
$id = $_POST['id'] ?? '';
$birth = $_POST['birth'] ?? '';
$password = hash('sha512',$userpw);
$email = $_POST['email'] ?? '';
$number = $_POST['number'] ?? '';
$reg_date = $_POST['reg_date'] ?? '';
$member_detail = $_POST['member_detail'] ?? '';
$grade = $_POST['grade'] ?? '';
$cover_image = $_FILES['cover_image'] ?? '';

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

$sql = "INSERT INTO members
(name, id, birth, password, email, number, reg_date, member_detail, grade, cover_image)
VALUES
('$name', '$id', '$birth', '$password', '$email', '$number', '$reg_date', '$member_detail', '$grade', '$cover_image')";

echo $sql;

$member_result = $mysqli->query($sql);
if ($member_result) {
  echo "<script>
    alert('회원이 등록되었습니다.');
    history.back();
    </script>";
} else {
  echo "<script>
    alert('등록에 실패했습니다.');
    history.back();
  </script>";
}

$mysqli->close();
?>
