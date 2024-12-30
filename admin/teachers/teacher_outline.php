<?php
$title = '강사 개요';
$sales_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/sales.css\" rel=\"stylesheet\">";
$chart_js = "<script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

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


//강사 관련
$teacher_count_sql = "SELECT COUNT(*) AS total_teachers FROM teachers";
$teacher_count = $mysqli->query($teacher_count_sql); 
$t_count = $teacher_count->fetch_object();
//print_r($t_count); stdClass Object ( [total_teachers] => 14 )

//2024에 가입한 강사 수
$teacher_2024_register = "SELECT COUNT(*) AS total_2024_teachers FROM teachers WHERE YEAR(reg_date) = 2024";
$teacher_2024_count = $mysqli->query($teacher_2024_register); 
$teacher_2024 = $teacher_2024_count->fetch_object();
// print_r($teacher_2024);

//2023에 가입한 강사 수
$teacher_2023_register = "SELECT COUNT(*) AS total_2023_teachers FROM teachers WHERE YEAR(reg_date) = 2023";
$teacher_2023_count = $mysqli->query($teacher_2023_register); 
$teacher_2023 = $teacher_2023_count->fetch_object();
// print_r($teacher_2023);

//매출 상위 5명 강사 
$sql = "SELECT * 
        FROM teachers
        ORDER BY year_sales DESC
        LIMIT 5;"; // 상위 5명 제한

$result = $mysqli->query($sql);

$name = [];
$sale = [];

// 상위 5명 데이터 추출
while ($data = $result->fetch_object()) {
  $name[] = $data->name; // x축에 사용할 강사 이름
  $sale[] = $data->year_sales; // y축에 사용할 매출 데이터
}




?>
<div class="container sales my-4">
  <!-- 강의 정보 섹션 -->
   
  <div class="row g-4">
    <div class="col-md-4">
      <div class="sales_box">
        <dl class="">
          <dt>총 강사 수</dt>
          <dd>
            <div><?= $t_count->total_teachers ?>명</div>
          </dd>
        </dl>
      </div>
    </div>
    <div class="col-md-4">
      <div class="sales_box">
        
        <dl>
          <dt>올해 신입 강사 수</dt>
          <dd>
            <div><?= $teacher_2024->total_2024_teachers ?>명</div>
          </dd>
        </dl>
      </div>
    </div>
    <div class="col-md-4">
      <div class="sales_box">
        <dl>
          <dt>올해 탈퇴 강사 수</dt> <!--작년 총 강사 숫자 + 올해 신입 강사 숫자 - 지금총 강사 수 =-->
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
      <dt>올해 매출 TOP 5 강사</dt>
      <canvas id="top5TeachersChart" style="width: 100%; height: 300px;"></canvas>
    </dl>

  </div>
</div>

</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // top5 강사 매출 차트
    const teacherNames = <?php echo json_encode($name); ?>; // 강사 이름 배열
    const teacherSales = <?php echo json_encode($sale); ?>; // 매출 데이터 배열

    const ctx = document.getElementById('top5TeachersChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar', // 차트 유형: 막대형
      data: {
        labels: teacherNames, // x축 레이블 (강사 이름)
        datasets: [{
          label: '매출 (원)', // 범례
          data: teacherSales, // y축 데이터 (매출)
          backgroundColor: [
            '#FF6B6B', // 첫 번째 막대 색상
            '#6BCBFF', // 두 번째 막대 색상
            '#A28DFF', // 세 번째 막대 색상
            '#6BD1FF', // 네 번째 막대 색상
            '#3666FF' // 다섯 번째 막대 색상
          ],
          borderWidth: 0, // 테두리 두께 없음
          borderRadius: 10 // 막대 끝을 둥글게
        }]
      },
      options: {
        responsive: true, // 반응형
        maintainAspectRatio: false, // 그래프 비율 유지 해제
        plugins: {
          legend: {
            display: false // 범례 비활성화
          },
          title: {
            display: true,
            text: '강사 매출 상위 5명',
            font: {
              size: 14, // 제목 폰트 크기
              weight: 'bold' // 제목 폰트 굵기
            },
            padding: {
              top: 10,
              bottom: 30 // 제목과 그래프 간격
            }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                // 툴팁에 콤마(,)를 추가하여 읽기 쉽게 표시
                return `매출: ${context.raw.toLocaleString()} 원`;
              }
            }
          }
        },
        scales: {
          x: {
            grid: {
              display: false // x축 격자선 비활성화
            },
            ticks: {
              font: {
                size: 14 // x축 폰트 크기
              },
              color: '#333' // x축 텍스트 색상
            }
          },
          y: {
            beginAtZero: true, // y축 0부터 시작
            grid: {
              borderDash: [7, 7], // 점선 형태의 격자선
              color: '#ccc' // y축 격자선 색상
            },
            ticks: {
              font: {
                size: 12 // y축 폰트 크기
              },
              color: '#333', // y축 텍스트 색상
              callback: function(value) {
                // y축 숫자에 콤마(,) 추가
                return `${value.toLocaleString()} 원`;
              }
            }
          }
        }
      }
    });
  });
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>