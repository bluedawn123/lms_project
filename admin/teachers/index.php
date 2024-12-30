<?php
$title = '대시보드';
$admin_index_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/admin_index.css\" rel=\"stylesheet\">";
$chart_js = "<script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/teachers/inc/header.php');

// print_r($_SESSION); //Array ( [TUIDX] => 3 [TUID] => kwak [TUNAME] => 곽튜브 ) 
$id = $_SESSION['TUIDX']; //print_r($id); 3


$un_read_message_sql = "SELECT COUNT(*) AS unread_count FROM `toteachermessages` WHERE `receiver_id` = $id AND `is_read` = 0";
$result = $mysqli->query($un_read_message_sql); // 쿼리 실행 결과
$data = $result->fetch_object();

//print_r($data); stdClass Object ( [unread_count] => 1 )

if ($data && $data->unread_count > 0) {
  $unread_count = $data->unread_count; // 읽지 않은 쪽지 개수
  echo "
    <script>
      alert('안 읽은 쪽지가 {$unread_count}개 있습니다.');
    </script>
  ";
}

?>

<div class="dashboard container m-0">
  <!-- Summary Section -->
  <div class="row">
    <div class="col-md-4 amount_teacher">
      <div class="card border-0 shadow-sm p-3">
        <div class="card-header bg-white border-0 pb-2 d-flex">
          <h6 class="mb-0 fw-bold text-primary">아직 구현 전</h6>
          <small class="ms-2"></small>
        </div>
        <div class="card-body">
          <div class="row text-center">
            <div class="col-4">
              <h6 class="text-primary"></h6>
              <p class="mb-0">
                <span class="text-primary d-block"></span>
                <span class="text-dark d-block"></span>
              </p>
            </div>
            <div class="col-4">
              <h6 class="text-primary"></h6>

            </div>
            <div class="col-4">
              <h6 class="text-danger"></h6>
              <p class="mb-0">
                <span class="text-danger"></span>
                <span class="text-dark"></span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 amount_member">
      <div class="card border-0 shadow-sm p-3">
        <div class="card-header bg-white border-0 pb-2 d-flex">
          <h6 class="mb-0 fw-bold text-primary">전체 회원 관리</h6>
          <small class="ms-2"></small>
        </div>
        <div class="card-body">
          <div class="row text-center">
            <div class="col-4">
              <h6 class="sub_tt text-primary"></h6>
              <p class="mb-0">
                <span class="text-primary d-block"></span>
                <span class="text-dark d-block"></span>
              </p>
            </div>
            <div class="col-4">
              <h6 class="text-primary"></h6>
            </div>
            <div class="col-4">
              <h6 class="text-danger"></h6>
              <p class="mb-0">
                <span class="text-dark"></span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 amount_sales">
      <div class="card border-0 shadow-sm p-3">
        <div class="card-header bg-white border-0 pb-2 d-flex">
          <h6 class="mb-0 fw-bold  text-primary">매출 관리</h6>
        </div>
        <div class="card-body">
          <canvas id="top5TeachersChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>






<script>
 

</script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/teachers/inc/footer.php');
?>