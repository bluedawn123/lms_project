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
$courseArr = [];
$course_sql = "SELECT cm.*, m.name,  l.title, l.expiration_day  
FROM courses_management cm
JOIN members m ON m.mid = cm.mid
JOIN lecture_list l ON l.lid = cm.lid";
$course_result = $mysqli->query($course_sql);
while ($row = $course_result->fetch_object()) {
  $courseArr[] = $row;
}


?>

<table class="table table-hover mb-3">
  <thead>
    <tr>
      <th scope="col">회원명</th>
      <th scope="col">강의명</th>
      <th scope="col">진도율</th>
      <th scope="col">시작일</th>
      <th scope="col">종료일</th>
      <th scope="col">상태</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($courseArr as $course) {

    ?>
      <tr>
        <td><?= $course->name ?></td>
        <td><a href="course_view.php?lid=<?= $course->lid ?>"><?= $course->title ?></a></td>
        <td><?= $course->progress ?></td>
        <td><?= $course->start_date ?></td>
        <td><?= $course->expiration_day ?></td>
        <td><?= $course->status ?></td>
      </tr>
    <?php
    }
    ?>
  </tbody>
  <pre>
목적: 전체 수강 신청 내역 및 학습 진행 상황을 확인.
주요 기능:
수강생 이름, 이메일, 등록일, 강의명, 수강 상태(진행 중, 완료, 중단 등) 표시.
진도율(%): 수강자가 얼마나 강의를 들었는지 표시. (ex: 진행률 45%)
필터 기능:
강의별로 필터링.
수강 상태(예: 진행 중, 미완료, 완료)별 필터링.
등록일 또는 최근 학습일 기준 정렬.
관리 기능:
상세보기: 수강생의 구체적인 학습 기록(진도율, 마지막 학습 날짜, 학습한 섹션 등) 확인.
메시지/이메일 보내기 버튼:
선택한 수강생 또는 그룹에게 진도 독려 메시지 발송.

</pre>

  <?php
  include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
  ?>