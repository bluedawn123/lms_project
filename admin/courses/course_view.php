<?php
$title = '강의 보기';
$reset_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/reset.css\" rel=\"stylesheet\">";
$lecture_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/lecture.css\" rel=\"stylesheet\">";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

$id = isset($_SESSION['AUID']) ? $_SESSION['AUID']  : $_SESSION['TUID'];
if (!isset($id)) {
  echo "
    <script>
      alert('관리자로 로그인해주세요');
      location.href = '../login.php';
    </script>
  ";
}



$tuition = '';
$member_data = [];
$lid = $_GET['lid'];
$course_sql = "SELECT * FROM courses_management WHERE lid = $lid";
$course_result = $mysqli->query($course_sql);
while ($course_row = $course_result->fetch_object()) {
  $member_sql = "SELECT * FROM members WHERE mid = $course_row->mid";
  $member_result = $mysqli->query($member_sql);
  $member_data[] = $member_result->fetch_object();
}




$sql = "SELECT * FROM lecture_list WHERE lid = $lid";
$result = $mysqli->query($sql);
$data = $result->fetch_object();




$lcid = $data->lcid;
$cate_sql = "SELECT * FROM lecture_category WHERE lcid = $lcid";
if ($cate_result = $mysqli->query($cate_sql)) {
  $cate_data = $cate_result->fetch_object();
  $pcode_name_sql = "SELECT name FROM lecture_category WHERE code = '{$cate_data->pcode}' AND pcode = '{$cate_data->ppcode}'";
  $ppcode_name_sql = "SELECT name FROM lecture_category WHERE code = '{$cate_data->ppcode}'";

  $pcode_result = $mysqli->query($pcode_name_sql);
  $ppcode_result = $mysqli->query($ppcode_name_sql);

  $pcode_name = ($pcode_result && $pcode_result->num_rows > 0) ? $pcode_result->fetch_object()->name : "Unknown";
  $ppcode_name = ($ppcode_result && $ppcode_result->num_rows > 0) ? $ppcode_result->fetch_object()->name : "Unknown";
}



?>

<section class="info">
  <div>
    <div class="catogory mb-1 ">
      <p class="small-font"><?= $ppcode_name . ' / ' . $pcode_name . ' / ' . $cate_data->name ?></p>
    </div>
    <div class="title mb-2">
      <h4 class="normal-font"><?= $data->title ?></h4>
      <p class="name text-decoration-underline"><?= $data->t_id ?></p>
    </div>
    <div class="learnObj">
      <h6>학습 목표</h6>
      <p class="small-font"><?= $data->learning_obj ?></p>
    </div>
  </div>
  <ul>
    <li class=""> <img src="../img/icon-img/review.svg" alt=""> 5점 <span class="text-decoration-underline small-font">수강평 보기</span></li>
    <li class="like"><img src="../img/icon-img/Heart.svg" alt="">500+</li>
    <li class="tag"><?= !empty($data->lecture_tag) ? "<span> {$data->lecture_tag}</span>" : '' ?> </li>
  </ul>
</section>
<section class="desc row">
  <h3 class="mb-3">수강생</h3>
  <table class="table table-hover mb-3">
    <thead>
      <tr>
        <th scope="col">회원명</th>
        <th scope="col">이메일</th>
        <th scope="col">진행율</th>
        <th scope="col">가입일</th>
        <th scope="col">등급</th>
        <th scope="col">마지막 로그인</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($member_data as $member) {

      ?>
        <tr>
          <td><?= $member->name ?></td>
          <td><?= $member->email ?></td>
          <td><?= $member->progress ?></td>
          <td><?= $member->reg_date ?></td>
          <td><?= $member->grade ?></td>
          <td><?= $member->last_login ?></td>
        </tr>
      <?php
      }
      ?>
    </tbody>
  </table>
</section>

<aside>
  <div class="lecture_coverImg">
    <img src="<?= $data->cover_image ?>" alt="">
  </div>
</aside>



<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>