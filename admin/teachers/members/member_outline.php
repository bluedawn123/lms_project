<?php
$title = '회원 개요';
$sales_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/sales.css\" rel=\"stylesheet\">";
$chart_js = "<script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/teachers/inc/header.php');

$manage_sql = "SELECT * FROM sales_management";
$manage_result = $mysqli->query($manage_sql);
if($manage_result){
  $manage_data = $manage_result->fetch_object();
}

$data_sql = "SELECT * FROM lecture_data";
$data_result = $mysqli->query($data_sql);
$html = '';
while ($data_row = $data_result->fetch_object()) {
  // $data_data[] = $data_row;
  $time = $data_row->lecture_time;
  list($hours, $minutes) = explode(":", $time);
  $lectureTime = intval($hours) . "시간 " . intval($minutes) . "분";

  $time1 = $data_row->lecture_avgwatch;
  list($hours, $minutes) = explode(":", $time);
  $lectureAvgwatch = intval($hours) . "시간 " . intval($minutes) . "분";
  
  $html .= " <tr class=\"border-bottom border-secondary-subtitle\">
        <th>{$data_row->lecture_name}</th>
        <td>{$lectureTime}</td>
        <td>{$data_row->lecture_number}개</td>
        <td>{$data_row->lecture_date}일</td>
        <td>{$lectureAvgwatch}</td>
      </tr>";
}

$month = date("n");
for ($i = 2; $i <= 3; $i++) {
  $monthArr[] = date("n", strtotime("-{$i} months", $month));
}

$month_data = [];

foreach ($monthArr as $month) {
  $month_sql = "SELECT  sales FROM sales_monthly WHERE month = '{$month}월' ";
  $month_result = $mysqli->query($month_sql);
  while ($month_row = $month_result->fetch_object()) {
    array_push($month_data, $month_row);
  }
}

$current_month = $month_data[0]->sales;
$previous_month = $month_data[1]->sales;

$month_diff = $current_month - $previous_month;
$month_per = ($month_diff / $previous_month) * 100 ;

$inc_sales = $month_diff > 0  ? "<span class='blue'>". number_format($month_diff) ."원 ($month_per%) <i class=\"fa-solid fa-arrow-up\"></i></span>" : "<span class='red'>". number_format($month_diff) ."원 ($month_per%) <i class=\"fa-solid fa-arrow-down\"></i></span>";


//회원 관련
$member_count_sql = "SELECT COUNT(*) AS total_members FROM members";
$member_count = $mysqli->query($member_count_sql); 
$m_count = $member_count->fetch_object();
//print_r($m_count); 

//2024에 가입한 회원 수
$member_2024_register = "SELECT COUNT(*) AS total_2024_members FROM members WHERE YEAR(reg_date) = 2024";
$member_2024_count = $mysqli->query($member_2024_register); 
$member_2024 = $member_2024_count->fetch_object();
// print_r($member_2024);

//2023에 가입한 회원 수
$member_2023_register = "SELECT COUNT(*) AS total_2023_members FROM members WHERE YEAR(reg_date) = 2023";
$member_2023_count = $mysqli->query($member_2023_register); 
$member_2023 = $member_2023_count->fetch_object();
// print_r($member_2023);


//월별 데이터
// 월별 데이터 쿼리
$sql = "SELECT DATE_FORMAT(reg_date, '%Y-%m') AS month, COUNT(*) AS member_count
        FROM members
        GROUP BY DATE_FORMAT(reg_date, '%Y-%m')
        ORDER BY month ASC";
$result = $mysqli->query($sql);

$months = [];
$counts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $months[] = $row['month'];
        $counts[] = $row['member_count'];
    }
}

// 누적 회원 수 계산
$cumulativeCounts = [];
$runningTotal = 0;
foreach ($counts as $count) {
    $runningTotal += $count;
    $cumulativeCounts[] = $runningTotal;
}
?>



<div class="container sales my-4">
  <!-- 강의 정보 섹션 -->
  <div class="row g-4">
    <div class="col-md-4">
      <div class="sales_box">
        <dl class="">
          <dt>총 회원 수</dt>
          <dd>
            <div><?= $m_count->total_members ?>명</div>
          </dd>
        </dl>
      </div>
    </div>
    <div class="col-md-4">
      <div class="sales_box">
        
        <dl>
          <dt>올해 신입 회원 수</dt>
          <dd>
            <div><?= $member_2024->total_2024_members ?>명</div>
          </dd>
        </dl>
      </div>
    </div>
    <div class="col-md-4">
      <div class="sales_box">
        <dl>
          <dt>올해 탈퇴 회원 수</dt> <!--작년 총 회원 숫자 + 올해 신입 회원 숫자 - 지금총 회원 수 =-->
          <dd>
            <div>0명</div>
          </dd>
        </dl>
      </div>
    </div>
  </div>

  <!-- 총 매출 섹션 -->
  <div class="row g-4">
    <div class="col-12">
      <div class="sales_box w-100">
        <dl>
          <dt>총 매출</dt>
          <dd>
            <div ><?= number_format($manage_data->total_sales) ?>원 </div>  <!--성우 씨 꺼 가져다 씀-->
          </dd>
        </dl>
      </div>
    </div>
  </div>

  <!-- 차트 섹션 -->
  <div class="row g-4">
    <div class="col-md-6">
      <div class="sales_chart">
        <dl>
          <dt>월별 신입 회원 증가 그래프</dt>
          <dd class="mt-5"><canvas id="registrationChart"></canvas></dd>
        </dl>
      </div>
    </div>
    <div class="col-md-6">
      <div class="sales_chart">
        <dl>
          <dt>모든 회원 증가 그래프</dt>
          <dd class="mt-5"><canvas id="registrationChart2"></canvas></dd>
        </dl>
      </div>
    </div>
  </div>

  <!-- 강의 정보 섹션 -->
  <div class="row g-4">
    <div class="col-md-6">
      <div class="sales_chart">
        <dl>
          <dt>올해 회원 평균 매출 / 월별 회원 평균 매출</dt>
          <dd class="mt-5">
            <div class="chart-box">
              <canvas id="chart1"></canvas>
            </div>
            <div class="chart-box">
              <canvas id="chart2"></canvas>
            </div>
            <div class="chart-box">
              <canvas id="chart3"></canvas>
            </div>
          </dd>
        </dl>
      </div>
    </div>
    <div class="col-md-6">
      <div class="sales_chart">
        <dl>
          <dt>이번달 매출 TOP 5 회원</dt>
          <dd>
            <table class="table table-hover data_table">
              <thead>
                <tr>
                  <th scope="col">강의명</th>
                  <th scope="col">영상 시간</th>
                  <th scope="col">영상 개수</th>
                  <th scope="col">기간</th>
                  <th scope="col">평균 시청 시간</th>
                </tr>
              </thead>
              <tbody>
                <?= $html ?>
              </tbody>
            </table>
          </dd>
        </dl>
      </div>
    </div>
  </div>

  <!-- 종합 데이터 섹션 -->
  <!-- <div class="row g-4">
    <div class="col-12">
      <div class="sales_chart">
        <dl>
          <dt>종합 데이터</dt>
          <dd>
            <canvas id="salesChart" width="1200" height="300"></canvas>
          </dd>
        </dl>
      </div>
    </div>
  </div> -->
</div>


<script>
 // PHP 데이터를 JavaScript로 전달
const months = <?php echo json_encode($months); ?>;
const counts = <?php echo json_encode($counts); ?>;
const cumulativeCounts = <?php echo json_encode($cumulativeCounts); ?>;

// 월별 회원 증가 그래프 (선형 차트)
const ctx1 = document.getElementById('registrationChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: '월별 등록 회원 수',
            data: counts,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: '회원 수'
                }
            },
            x: {
                title: {
                    display: true,
                    text: '월'
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            title: {
                display: true,
                text: '회원 월별 등록 현황'
            }
        }
    }
});

// 누적 회원 증가 그래프 (막대 차트)
const ctx2 = document.getElementById('registrationChart2').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: '누적 회원 수',
            data: cumulativeCounts,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: '누적 회원 수'
                }
            },
            x: {
                title: {
                    display: true,
                    text: '월'
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            title: {
                display: true,
                text: '회원 누적 증가 현황'
            }
        }
    }
});
</script>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php'); ?>