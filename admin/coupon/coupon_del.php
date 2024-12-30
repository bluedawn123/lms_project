<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

if(!isset($_SESSION['AUID'])){
  echo "
    <script>
      alert('관리자로 로그인해주세요');
      location.href = '../login.php';
    </script>
  ";
}
$cid = $_GET['cid'];
if (!isset($cid)) {
  echo "<script>alert('쿠폰정보가 없습니다.'); 
  location.href = 'coupon_list.php';</script>";
}

//products 테이블에서 cid 컬럼이 $cid와 같은 데이터 삭제, 썸네일 삭제
$coupon_image_sql = "SELECT coupon_image FROM coupons WHERE cid = $cid";
$coupon_result = $mysqli->query($coupon_image_sql);
$coupon_data = $coupon_result->fetch_object();
$coupon_image_url = $coupon_data->coupon_image;

unlink($_SERVER['DOCUMENT_ROOT'].$coupon_image_url);

$coupon_del_sql = "DELETE FROM coupons WHERE cid = $cid";
$coupon_del_result = $mysqli->query($coupon_del_sql);

//삭제 완료후 쿠폰 목록으로 이동
if($coupon_del_result){
  echo "<script>
    alert('쿠폰이 삭제되었습니다.');
    location.href = 'coupon_list.php';
  </script>";
}

?>