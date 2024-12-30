<?php
$title = "강사 목록";
$teacherOverView_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/teacherOverView.css\" rel=\"stylesheet\">";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

if (!isset($_SESSION['AUID'])) {
  echo "
    <script>
      alert('관리자로 로그인해주세요');
      location.href = '../index.php';
    </script>
  ";
}

//매출순
$sql = "SELECT * FROM lecture_list WHERE ispopular = 1";
$result = $mysqli->query($sql);
$dataArr = [];
while ($data = $result->fetch_object()) {
  $dataArr[] = $data;
}
$dataArr = array_slice($dataArr, 0, 4);

//모든 강사(8개로 자름)
$sql2 = "SELECT * FROM lecture_list WHERE ispremium = 1";
$result2 = $mysqli->query($sql2);
$dataArr2 = [];
while ($data2 = $result2->fetch_object()) {
  $dataArr2[] = $data2;
}
$dataArr2 = array_slice($dataArr2, 0, 8);

//최신강사순
$sql3 = "SELECT * FROM lecture_list WHERE isrecom = 1";
$result3 = $mysqli->query($sql3);
$dataArr3 = [];
while ($data3 = $result3->fetch_object()) {
  $dataArr3[] = $data3;
}

// 최대 4개의 카드만 표시
$dataArr3 = array_slice($dataArr3, 0, 4);

//최신강사순
$sql4 = "SELECT * FROM lecture_list WHERE isfree = 1";
$result4 = $mysqli->query($sql4);
$dataArr4 = [];
while ($data4 = $result4->fetch_object()) {
  $dataArr4[] = $data4;
}

// 최대 4개의 카드만 표시
$dataArr4 = array_slice($dataArr4, 0, 4);


?>

<div class="container">
  <div class="">
    <h2>인기강의</h2>
    <div class="d-flex flex-wrap"> <!-- Flex 컨테이너 -->
      <?php
      foreach ($dataArr as $item) {
      ?>
        <div class="card m-2" style="width:18rem; height:25rem;"> <!-- 개별 카드 -->
          <img class="card-img-top" src="<?= $item->cover_image ?>" alt="Card image cap" width="250" height="250">
          <div class="card-body">
            <h5 class="card-title"><?= $item->title ?></h5>
            <p class="card-text"><?= $item->t_id ?></p>
            <a href="lecture_view.php?lid=<?= $item->lid; ?>" class="btn btn-primary">강사 상세정보</a>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
  </div>
  <hr>
  <div class="">
    <h2>추천강의</h2>
    <div class="d-flex flex-wrap"> <!-- Flex 컨테이너 -->
      <?php
      foreach ($dataArr3 as $item) {
      ?>
        <div class="card m-2" style="width:18rem; height:25rem;"> <!-- 개별 카드 -->
          <img class="card-img-top" src="<?= $item->cover_image ?>" alt="Card image cap" width="250" height="250">
          <div class="card-body">
            <h5 class="card-title"><?= $item->title ?></h5>
            <p class="card-text"><?= $item->t_id ?></p>
            <a href="lecture_view.php?lid=<?= $item->lid; ?>" class="btn btn-primary">강사 상세정보</a>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
  </div>
  <hr>
  <div class="">
    <h2>프리미엄 강의</h2>
    <div class="d-flex flex-wrap"> <!-- Flex 컨테이너 -->
      <?php
      foreach ($dataArr2 as $item) {
      ?>
        <div class="card m-2" style="width:18rem; height:25rem;"> <!-- 개별 카드 -->
          <img class="card-img-top" src="<?= $item->cover_image ?>" alt="Card image cap" width="250" height="250">
          <div class="card-body">
            <h5 class="card-title"><?= $item->title ?></h5>
            <p class="card-text"><?= $item->t_id ?></p>
            <a href="lecture_view.php?lid=<?= $item->lid; ?>" class="btn btn-primary">강사 상세정보</a>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
  </div>
  <div class="">
    <h2>무료 강의</h2>
    <div class="d-flex flex-wrap"> <!-- Flex 컨테이너 -->
      <?php
      foreach ($dataArr2 as $item) {
      ?>
        <div class="card m-2" style="width:18rem; height:25rem;"> <!-- 개별 카드 -->
          <img class="card-img-top" src="<?= $item->cover_image ?>" alt="Card image cap" width="250" height="250">
          <div class="card-body">
            <h5 class="card-title"><?= $item->title ?></h5>
            <p class="card-text"><?= $item->t_id ?></p>
            <a href="lecture_view.php?lid=<?= $item->lid; ?>" class="btn btn-primary">강사 상세정보</a>
          </div>
        </div>
      <?php
      }
      ?>
    </div>
  </div>
</div>



<script>



</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>