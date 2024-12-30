<?php
$title = '수강 관리';
// $coupon_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/admin/css/coupon.css\" rel=\"stylesheet\" >";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

$id = $_SESSION['AUID'];
if (!isset($id)) {
  echo "
    <script>
      alert('관리자로 로그인해주세요');
      location.href = '../login.php';
    </script>
  ";
}

?>
<pre>
목적: 수료 기한 내에 완강이 어려운 수강생을 선별해 집중 관리.
주요 기능:
자동 선별 기준:
남은 수료 기한 대비 진도율이 낮은 수강생.
7일 이상 학습 활동이 없는 수강생.
알림 기능:
관리자가 지정한 조건에 따라 자동 알림 발송.
예: "최근 10일 동안 학습 기록이 없습니다. 강의를 이어가세요!"
관리 기능:
개인 또는 전체 대상 메시지/이메일 보내기.
학습 목표 또는 마감일 설정 리마인더.

</pre>


<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>