<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/dbcon.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/common.php');

$coupon_name = $_POST['coupon_name'] ?? '';
$coupon_content = $_POST['coupon_content'] ?? '';
$coupon_image = $_POST['coupon_image'] ?? '';
$coupon_type = $_POST['coupon_type'] ?? '';
$coupon_price = $_POST['coupon_price'] ?? 0;
$coupon_ratio = $_POST['coupon_ratio'] ?? 0;
$status = isset($_POST['coupon_activate']) ? 1 : 0;
$startdate = $_POST['startdate'] ?? date('Y-m-d');
$enddate = $_POST['enddate'] ?? date('Y-m-d', strtotime('+1 year'));
$userid = $_SESSION['AUID'] ?? 'admin';

// 이미지 업로드 처리
if (isset($_FILES['coupon_image']) && $_FILES['coupon_image']['error'] == UPLOAD_ERR_OK) {
  $fileUploadResult = fileUpload($_FILES['coupon_image'], 'image');
  if ($fileUploadResult) {
    $coupon_image = $fileUploadResult; // 업로드된 파일 경로를 변수에 저장
  } else {
    echo "<script>
              alert('파일 첨부할 수 없습니다.');
              history.back();
          </script>";
    exit;
  }
}


$sql = "INSERT INTO coupons (coupon_name, coupon_image, coupon_content, coupon_type, coupon_price, coupon_ratio, status, startdate, enddate, userid)
        VALUES ('$coupon_name', '$coupon_image', '$coupon_content', '$coupon_type', $coupon_price, $coupon_ratio, $status, '$startdate', '$enddate', '$userid')";

$result = $mysqli->query($sql); 
if($result){
  echo "
    <script>
      alert('쿠폰 등록 완료');
      location.href = 'coupon_list.php';
    </script>
  ";
}

$mysqli->close();
?>