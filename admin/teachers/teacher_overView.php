<?php
$title = "강사 목록";
$teacherOverView_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/teacherOverView.css\" rel=\"stylesheet\">";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

if(!isset($_SESSION['AUID'])){
  echo "
    <script>
      alert('관리자로 로그인해주세요');
      location.href = '../index.php';
    </script>
  ";
}

//매출순
$sql = "SELECT * FROM teachers ORDER BY year_sales DESC";
$result = $mysqli->query($sql); 
$dataArr = [];
while($data = $result->fetch_object()){
  $dataArr[] = $data;
}
$dataArr = array_slice($dataArr, 0, 4);

//모든 강사(8개로 자름)
$sql2 = "SELECT * FROM `teachers` WHERE 1";
$result2 = $mysqli->query($sql2); 
$dataArr2 = [];
while($data2 = $result2->fetch_object()){
  $dataArr2[] = $data2;
}
$dataArr2 = array_slice($dataArr2, 0, 8);

//최신강사순
$sql3 = "SELECT * FROM teachers ORDER BY reg_date DESC";
$result3 = $mysqli->query($sql3); 
$dataArr3 = [];
while ($data3 = $result3->fetch_object()) {
  $dataArr3[] = $data3;
}

// 최대 4개의 카드만 표시
$dataArr3 = array_slice($dataArr3, 0, 4);



?>

<div class="container">
  <div class="mb-5">
    <h3 class="text-center mb-4">인기강사</h3>
    <div class="d-flex flex-wrap justify-content-center gap-4"> <!-- Flex 컨테이너 -->
      <?php foreach ($dataArr as $item): ?>
      <div class="card shadow-sm" style="width: 18rem;"> <!-- 개별 카드 -->
        <div class="card-header p-0 overflow-hidden" style="height: 350px;">
          <img class="card-img-top img-fluid" src="<?= $item->cover_image ?>" alt="Card image cap" style="object-fit: cover; height: 100%; width: 100%;">
        </div>
        <div class="card-body">
          <h5 class="card-title text-primary fw-bold mb-3"><?= $item->name ?></h5>
          <p class="mb-2"><strong>강사 아이디:</strong> <?= $item->id ?></p>
          <p class="mb-2"><strong>강사 등급:</strong> <?= $item->grade ?></p>
          <p class="mb-3"><strong>학생수:</strong> <?= number_format($item->student_number) ?></p>
          <a href="teacher_view.php?tid=<?= $item->tid; ?>" class="btn btn-outline-primary w-100">강사 상세정보</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <hr>

  <div class="mb-5">
    <h3 class="text-center mb-4">신입강사</h3>
    <div class="d-flex flex-wrap justify-content-center gap-4">
      <?php foreach ($dataArr3 as $item): ?>
      <div class="card shadow-sm" style="width: 18rem;">
        <div class="card-header p-0 overflow-hidden" style="height: 350px;">
          <img class="card-img-top img-fluid" src="<?= $item->cover_image ?>" alt="Card image cap" style="object-fit: cover; height: 100%; width: 100%;">
        </div>
        <div class="card-body">
          <h5 class="card-title text-primary fw-bold mb-3"><?= $item->name ?></h5>
          <p class="mb-2"><strong>강사 아이디:</strong> <?= $item->id ?></p>
          <p class="mb-2"><strong>강사 등급:</strong> <?= $item->grade ?></p>
          <p class="mb-3"><strong>학생수:</strong> <?= number_format($item->student_number) ?></p>
          <a href="teacher_view.php?tid=<?= $item->tid; ?>" class="btn btn-outline-primary w-100">강사 상세정보</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <hr>

  <div class="mb-5">
    <h3 class="text-center mb-4">일반강사</h3>
    <div class="d-flex flex-wrap justify-content-center gap-4">
      <?php foreach ($dataArr2 as $item): ?>
      <div class="card shadow-sm" style="width: 18rem;">
        <div class="card-header p-0 overflow-hidden" style="height: 350px;">
          <img class="card-img-top img-fluid" src="<?= $item->cover_image ?>" alt="Card image cap" style="object-fit: cover; height: 100%; width: 100%;">
        </div>
        <div class="card-body">
          <h5 class="card-title text-primary fw-bold mb-3"><?= $item->name ?></h5>
          <p class="mb-2"><strong>강사 아이디:</strong> <?= $item->id ?></p>
          <p class="mb-2"><strong>강사 등급:</strong> <?= $item->grade ?></p>
          <p class="mb-3"><strong>학생수:</strong> <?= number_format($item->student_number) ?></p>
          <a href="teacher_view.php?tid=<?= $item->tid; ?>" class="btn btn-outline-primary w-100">강사 상세정보</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="text-center my-4">
    <a href="/qc/admin/teachers/teacher_list.php" class="btn btn-primary btn-lg">모든 강사 보러 가기</a>
  </div>
</div>



<script>
  

  
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>