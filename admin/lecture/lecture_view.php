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

$lid = $_GET['lid'];

$sql = "SELECT * FROM lecture_list WHERE lid = $lid";
$result = $mysqli->query($sql);
$data = $result->fetch_object();

if ($data->dis_tuition > 0) {
  $tui_val = number_format($data->tuition);
  $distui_val = number_format($data->dis_tuition);
  $tuition .= "<p class=\"text-decoration-line-through text-end \"> $tui_val 원 </p><p class=\"active-font\"> $distui_val 원 </p>";
} else {
  $tui_val = number_format($data->tuition);
  $tuition .=  "<p class=\"active-font\"> $tui_val 원 </p>";
}


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

switch ($data->difficult) {
  case 0:
    $diff = ' ';
    break;
  case 1:
    $diff = '입문';
    break;
  case 2:
    $diff = '초급';
    break;
  case 3:
    $diff = '중급';
    break;
  case 4:
    $diff = '고급';
    break;
  case 5:
    $diff = '전문';
    break;
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
<section class="desc row mt-5">
  <div class="col-8">
    <h3 class="subtitle mb-5"><?= $data->sub_title ?></h3>
    <hr>
    <p class="description mb-5"><?= $data->description ?></p>
  </div>
</section>

<aside>
  <div class="lecture_coverImg">
    <img src="<?= $data->cover_image ?>" alt="">
  </div>
  <div class="tuition">
    <div class="tuitionInfo">
      <h4>수강료</h4>
      <div>
        <?= $tuition ?>
      </div>
    </div>
    <div class="asideDesc">
      <dl class="tuitionDesc">
        <dt>강의시간</dt>
        <dd>2시간 40분</dd>
      </dl>
      <dl class="tuitionDesc">
        <dt>난이도</dt>
        <dd><?= $diff ?></dd>
      </dl>
      <dl class="tuitionDesc">
        <dt>등록일</dt>
        <dd><?= $data->regist_day ?></dd>
      </dl>
      <dl class="tuitionDesc">
        <dt>마감일</dt>
        <dd><?= $data->expiration_day ?></dd>
      </dl>
    </div>
  </div>
</aside>


<div class="d-flex gap-3 justify-content-end lecture_button">
  <a href="lecture_modify.php?lid=<?= $lid ?>" class=" btn btn-primary insert">수정</a>
  <a href="lecture_delete.php?lid=<?= $lid ?>" class=" btn btn-danger insert">삭제</a>
</div>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>