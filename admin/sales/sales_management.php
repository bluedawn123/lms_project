<?php
$title = '매출 관리';
$sales_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/sales.css\" rel=\"stylesheet\">";
$chart_js = "<script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

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

$total_sql = "SELECT SUM(total_price) AS total FROM lecture_order";
$total_result = $mysqli->query($total_sql);
if ($total_result) {
$total = $total_result->fetch_object()->total;
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


$monthArr = [];
for ($i = 0; $i <= 1; $i++) {
    $monthArr[] = date("n", strtotime("-{$i} months"));
}


$month_data = [];

foreach ($monthArr as $month) {
  $month_sql = "SELECT 
  DATE_FORMAT(createdate, '%c월') AS month,
  SUM(total_price) AS sales
  FROM lecture_order
  WHERE DATE_FORMAT(createdate, '%c월') = '{$month}월' 
  GROUP BY DATE_FORMAT(createdate, '%c월')
  ";
  $month_result = $mysqli->query($month_sql);
  if($month_result){
    while ($month_row = $month_result->fetch_object()) {
      array_push($month_data, $month_row);
    }

  }else{
    die("Query failed: " . $mysqli->error);
  }
}

$current_month = $month_data[0]->sales;
$previous_month = $month_data[1]->sales;

$month_diff = $current_month - $previous_month;
$month_per = floor(($month_diff / $previous_month) * 100);

$inc_sales = $month_diff > 0  ? "<span class='blue'>" . number_format($month_diff) . "원 ({$month_per}%) <i class=\"fa-solid fa-arrow-up\"></i></span>" : "<span class='red'>" . number_format($month_diff) . "원 ({$month_per}%) <i class=\"fa-solid fa-arrow-down\"></i></span>";

//회원 관련
$member_count_sql = "SELECT COUNT(*) AS total_members FROM memberskakao";
$member_count = $mysqli->query($member_count_sql);
$m_count = $member_count->fetch_object();

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
            <div><?= number_format($total) ?>원 </div>
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
      months.sort((a, b) => {
        const monthA = parseInt(a, 10);  // '1월'에서 '1'로 변환
        const monthB = parseInt(b, 10);  // '2월'에서 '2'로 변환
        return monthA - monthB;  // 숫자 기준으로 정렬
    });
    const salesSorted = months.map(month => {
            const monthData = data.find(item => item.month === month);
            return monthData ? monthData.sales : 'null';  // 해당 월의 매출 값
        });
      const monthly_data = document.getElementById('monthly_data');
      new Chart(monthly_data, {
        type: 'bar', // 막대 차트
        data: {
          labels: months, // x축 레이블
          datasets: [{
            label: '월 별 매출',
            data: salesSorted, // y축 데이터
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
      months.sort((a, b) => {
        const monthA = parseInt(a, 10);  // '1월'에서 '1'로 변환
        const monthB = parseInt(b, 10);  // '2월'에서 '2'로 변환
        return monthA - monthB;  // 숫자 기준으로 정렬
    });
      const datasets = names.map(course => {
        const sales = months.map(month => {
        // 해당 강의의 각 월에 대한 매출 값 찾기
          const item = data.find(item => item.course_name === course && item.month === month);
          return item ? item.sales : 'null';  // 데이터가 없으면 null으로 처리
      });

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