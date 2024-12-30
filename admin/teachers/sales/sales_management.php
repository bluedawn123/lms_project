<?php
$title = '매출 관리';
$sales_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/sales.css\" rel=\"stylesheet\">";
$chart_js = "<script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/teachers/inc/header.php');

$id = isset($_SESSION['TUID']) ? $_SESSION['TUID'] : null;
if (!isset($id)) {
  echo "
    <script>
      alert('강사로 로그인해주세요');
      location.href = '../login.php';
    </script>
  ";
}

$lecture_sql = "SELECT count(lid) AS cnt FROM lecture_list ";
$lecuter_result = $mysqli->query($lecture_sql);
if ($lecuter_result) {
  $lecture_data = $lecuter_result->fetch_object();
}

$sum_sql = "SELECT SUM(student_count) AS sc FROM lecture_list ";
$sum_result = $mysqli->query($sum_sql);
if ($sum_result) {
  $sum_data = $sum_result->fetch_object();
}

$avg_sql = "SELECT AVG(review) AS review FROM lecture_review ";
$avg_result = $mysqli->query($avg_sql);
if ($avg_result) {
  $avg_data = $avg_result->fetch_object();
}


$manage_sql = "SELECT * FROM sales_management";
$manage_result = $mysqli->query($manage_sql);
if ($manage_result) {
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
$month_per = ($month_diff / $previous_month) * 100;

$inc_sales = $month_diff > 0  ? "<span class='blue'>" . number_format($month_diff) . "원 ($month_per%) <i class=\"fa-solid fa-arrow-up\"></i></span>" : "<span class='red'>" . number_format($month_diff) . "원 ($month_per%) <i class=\"fa-solid fa-arrow-down\"></i></span>";

//회원 관련
$member_count_sql = "SELECT COUNT(*) AS total_members FROM members";
$member_count = $mysqli->query($member_count_sql);
$m_count = $member_count->fetch_object();
//2024에 가입한 강사 수
$member_2024_register = "SELECT COUNT(*) AS total_2024_members FROM members WHERE YEAR(reg_date) = 2024";
$member_2024_count = $mysqli->query($member_2024_register);
$member_2024 = $member_2024_count->fetch_object();
//2023에 가입한 강사 수
$member_2023_register = "SELECT COUNT(*) AS total_2023_members FROM members WHERE YEAR(reg_date) = 2023";
$member_2023_count = $mysqli->query($member_2023_register);
$member_2023 = $member_2023_count->fetch_object();

//강의관련
$lecture_num = "SELECT COUNT(*) AS total_lectures FROM `lecture_list`";
$lecture_nums = $mysqli->query($lecture_num);
$lecture_counts = $lecture_nums->fetch_object();
?>





<div class="container sales my-4">
  <!-- 강의 정보 섹션 -->
  <div class="row g-4">
    <div class="col-md-4">
      <div class="sales_box">
        <dl class="">
          <dt>강의 수</dt>
          <dd>
            <div><?= $lecture_counts->total_lectures ?> 개</div>
          </dd>
        </dl>
      </div>
    </div>
    <div class="col-md-4">
      <div class="sales_box">
        <dl>
          <dt>총 수강생</dt>
          <dd>
            <div><?= $m_count->total_members ?> 명</div>
          </dd>
        </dl>
      </div>
    </div>
    <div class="col-md-4">
      <div class="sales_box">
        <dl>
          <dt>평점</dt>
          <dd>
            <div><?= $avg_data->review ?> 점</div>
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
            <div><?= number_format($manage_data->total_sales) ?>원 </div>
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
          <dt>이번 달 수익</dt>
          <dd>
            <div class="mt-5"><?= number_format($current_month) ?> 원
              <br><?= $inc_sales ?>
            </div>
          </dd>
        </dl>
      </div>
    </div>
    <div class="col-md-6">
      <div class="sales_chart">
        <dl>
          <dt>종합 매출</dt>
          <dd class="mt-5"><canvas id="monthly_data"></canvas></dd>
        </dl>
      </div>
    </div>
  </div>

  <!-- 강의 정보 섹션 -->
  <div class="row g-4">
    <div class="col-md-6">
      <div class="sales_chart">
        <dl>
          <dt>강의 완강률</dt>
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
          <dt>강의 정보</dt>
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
  <div class="row g-4">
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
  </div>
</div>
<script>
  fetch('sales_data.php')
    .then(response => response.json())
    .then(data => {
      const months = data.map(item => item.month);
      const sales = data.map(item => item.sales);
      const monthly_data = document.getElementById('monthly_data');
      new Chart(monthly_data, {
        type: 'bar', // 막대 차트
        data: {
          labels: months, // x축 레이블
          datasets: [{
            label: '월 별 매출',
            data: sales, // y축 데이터
            backgroundColor: 'rgba(112, 134, 253, 1)',

          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    }).catch(error => console.error('Error fetching data:', error));

  fetch('sales_complate.php')
    .then(response => response.json())
    .then(data => {
      console.log(data);
      const colors = ['#0E5FD9', '#64A2FF', '#0040A1'];
      data.forEach((item, index) => {
        const ctx = document.getElementById(`chart${index + 1}`).getContext('2d');

        new Chart(ctx, {

          type: 'doughnut',
          data: {
            labels: ['완강률', '미강률'], // 레이블 설정
            datasets: [{
              data: [parseFloat(item.lecture_completion), 100 - parseFloat(item.lecture_completion)],
              backgroundColor: [colors[index], '#ffffff'], // 색상
            }]
          },
          options: {
            plugins: {
              title: {
                display: true,
                text: item.lecture_name // 강의 이름
              },
              legend: {
                display: false // 범례 비활성화
              },
              tooltip: {
                callbacks: {
                  label: function(tooltipItem) {
                    const value = tooltipItem.raw;
                    const total = tooltipItem.dataset.data.reduce((a, b) => a + b, 0);
                    const percentage = ((value / total) * 100).toFixed(1);
                    return `${value} (${percentage}%)`;
                  }
                }
              }
            },
            cutout: '70%' // 도넛 가운데 비율
          }
        });
      });
    }).catch(error => console.error('Error fetching data:', error));

  fetch('sales_course.php')
    .then(response => response.json())
    .then(data => {
      const months = [...new Set(data.map(item => item.month))];
      const names = [...new Set(data.map(item => item.course_name))];
      const colors = ['#0E5FD9', '#64A2FF', '#0040A1', '#4F38FF'];
      const datasets = names.map(course => {
        const sales = data
          .filter(item => item.course_name === course)
          .map(item => item.sales); // 강의별 매출 데이터

        return {
          label: course,
          data: sales,
          borderColor: colors,

        };
      });

      var ctx = document.getElementById('salesChart').getContext('2d');
      var salesChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: months, // x축: 월
          datasets: datasets, // y축: 강의 매출
          fill: false,
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top'
            },
            title: {
              display: true,
              text: '1년간 강의 매출 차트'
            }
          }
        }
      });

    }).catch(error => console.error('Error fetching data:', error));
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>