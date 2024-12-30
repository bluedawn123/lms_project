<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');

if (!isset($_SESSION['MemId'])) {
    echo "
        <script>
            alert('회원으로 로그인해주세요');
            location.href = '../index.php';
        </script>
    ";
}






$MemId = $_SESSION['MemId']; // print_r($MemId); 

$sql = "SELECT * FROM membersKakao WHERE memId = $MemId"; 
$result = $mysqli->query($sql); // 쿼리 실행 결과
$data = $result->fetch_object();

$MemEmail = $_SESSION['MemEmail']; // 사용자 이메일

$sql = "
    SELECT 
        o.odid AS order_id,
        o.total_price,
        o.status AS order_status,
        o.createdate AS order_date,
        l.lid AS lecture_id,
        l.title AS lecture_title,
        l.category AS lecture_category,
        l.cover_image AS lecture_image,
        l.t_id AS instructor_name,
        l.tuition AS original_price,
        l.dis_tuition AS discounted_price,
        l.sub_title AS lecture_summary,
        l.difficult AS difficulty,
        l.expiration_day AS lecture_expiration,
        COUNT(DISTINCT lw.lvid) AS total_videos,
        SUM(CASE WHEN lw.event_type = 'completed' THEN 1 ELSE 0 END) AS completed_videos
    FROM 
        lecture_order AS o
    JOIN 
        lecture_list AS l ON o.lid = l.lid
    LEFT JOIN 
        lecture_watch AS lw ON o.lid = lw.lid AND o.mid = lw.mid
    WHERE 
        o.mid = ?
    GROUP BY 
        o.lid
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $MemEmail); // 사용자 이메일 바인딩
$stmt->execute();

// 결과 가져오기
$result = $stmt->get_result();
$lectures = [];
while ($row = $result->fetch_assoc()) {
    $lectures[] = $row;
}
$stmt->close();

// SQL 쿼리 준비 (쿠폰 정보와 추가 정보 모두 가져오기)
$sql = "SELECT c.*, cu.use_max_date, cu.usedate, cu.reason
        FROM coupons_usercp cu
        JOIN coupons c ON cu.couponid = c.cid
        WHERE cu.userid = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $MemEmail); // 사용자 이메일 바인딩
$stmt->execute();



?>



<style>
  body {
  }
  .lecture-card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: none;
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.2s ease-in-out;
  }
  .lecture-card:hover {
    transform: scale(1.02);
  }
  .lecture-card img {
    height: 150px; /* 이미지 높이를 유지 */
    object-fit: cover;
  }
  .lecture-title {
    font-size: 1rem; /* 제목 크기를 유지 */
    font-weight: bold;
    color: #333;
  }
  .lecture-category {
    color: #6c757d;
    font-size: 0.8rem; /* 카테고리 글씨 크기를 유지 */
  }
  .progress {
    height: 15px; /* 프로그레스 바 높이를 유지 */
    border-radius: 10px;
  }
  .card-body {
    padding: 8px; /* 패딩 유지 */
  }
  .col-md-6 {
    flex: 0 0 auto;
    width: 33.3333%; /* 너비를 col-md-4 크기로 조정 */
  }
  .mb-4 {
    margin-bottom: 0.5rem; /* 아래 여백 유지 */
  }
</style>



<div class="container mt-5">
  <div class="row">
    <div class="col-3 mb-5">
      <div class="member_coverImg2 mb-3">
        <img src=" <?= $data->memProfilePath ?? '../img/icon-img/no-image.png'; ?>" id="coverImg" alt="" width="80" height="80" style="object-fit: cover; border-radius: 25%; border: 2px solid #ccc;">
      </div>
      <div>
        <h5>이름 : <?= $data->memName; ?></h5>
        <h6>아이디  : <?= $data->memEmail; ?></h6>
      </div>
      <hr>
      <div class="d-flex justify-content-center align-items-center gap-5">
        <div class="text-center">
          <p>총 강의 수</p>
          <p></p>
        </div>
        <div class="text-center">
          <p>평균 진도율</p>
          <p> </p>
        </div>
        <div class="text-center">
          <p>강의 평점</p>
          <p></p>
        </div>
      </div>
      <hr>
      <nav>
        <ul>
          <li class="my-2">
            <!-- 실제 이동 링크 -->
            <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/users/users_view.php?MemId=<?php echo htmlspecialchars($MemId); ?>" 
              class="text-decoration-none">
              대시보드
            </a>
          </li>
          <li class="my-2">
            <a href="#" class="text-decoration-none load-page" data-page="myProgress.php" id="myLectures">수강 관리</a>
          </li>
          <li class="my-2">
            <a href="#" class="text-decoration-none load-page" data-page="myInfo.php" id="myInfo">개인 정보</a>
          </li>
          <li class="my-2">
            <a href="#" class="text-decoration-none load-page" data-page="myCoupons.php" id="myCoupons">나의 쿠폰</a>
          </li>
          <li class="my-2">
            <a href="#" class="text-decoration-none load-page" data-page="myMessages.php" id="myCoupons">나의 쪽지</a>
          </li>
          <li class="my-2">
            <a href="#" class="text-decoration-none load-page" data-page="myWrites.php" id="myCoupons">나의 작성글</a>
          </li>
          <li class="my-2">
            <a href="#" class="text-decoration-none load-page" data-page="myPayments.php" id="myCoupons">결제내역</a>
          </li>
        </ul>
      </nav>
    </div>

    <div class="col-9 mb-3" id="main_content">
    <h4>나의 강의 현황</h4>
    <div class="row">
      <?php if (!empty($lectures)): ?>
        <?php foreach ($lectures as $lecture): 
          $progress = ($lecture['total_videos'] > 0) 
                      ? round(($lecture['completed_videos'] / $lecture['total_videos']) * 100, 2) 
                      : 0;
          $isCompleted = $progress >= 100;
          $remainingDays = isset($lecture['lecture_expiration']) 
              ? floor((strtotime($lecture['lecture_expiration']) - time()) / (60 * 60 * 24)) 
              : null;
        ?>
          <div class="col-md-6 mb-4">
            <div class="card lecture-card">
              <img src="<?= htmlspecialchars($lecture['lecture_image'] ?: '/qc/img/default-lecture.jpg'); ?>" class="card-img-top" alt="강의 이미지">
              <div class="card-body">
                <h5 class="lecture-title"><?= htmlspecialchars($lecture['lecture_title']); ?></h5>
                <p><strong>강사:</strong> <?= htmlspecialchars($lecture['instructor_name']); ?></p>
                <p class="text-muted"><strong>난이도:</strong> <?= htmlspecialchars($lecture['difficulty']); ?></p>
                <p><strong>진도율:</strong> <?= $progress; ?>%</p>
                <div class="progress mb-2">
                  <div class="progress-bar bg-success" role="progressbar" style="width: <?= $progress; ?>%;" aria-valuenow="<?= $progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="text-<?= $isCompleted ? 'success' : 'warning'; ?>">
                  <?= $isCompleted ? '강의 완료' : '수강 중'; ?>
                </p>
                <a href="/qc/lecture/lecture_view.php?lid=<?= htmlspecialchars($lecture['lecture_id']); ?>" class="btn btn-primary w-100 mt-3">강의로 이동</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>수강 중인 강의가 없습니다.</p>
      <?php endif; ?>
    </div>

    <!-- membersKakao 데이터 출력 -->
    <h4>나의 정보</h4>
    <div class="card mb-4">
        <div class="card-body">
          <h5 class="card-title"></h5>
          <p><strong>이름:</strong> <?= htmlspecialchars($data->memName); ?></p>
          <p><strong>아이디:</strong> <?= htmlspecialchars($data->memEmail); ?></p>
          <p><strong>가입일:</strong> <?= htmlspecialchars($data->memCreatedAt); ?></p>
          <p><strong>전화번호:</strong> <?= htmlspecialchars($data->number); ?></p>
        </div>
      </div>

  </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll(".load-page");
    const mainContent = document.getElementById("main_content");

    links.forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            const page = this.getAttribute("data-page");

            if (page) {
                fetch(page)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("페이지 로드 실패");
                        }
                        return response.text();
                    })
                    .then(html => {
                        mainContent.innerHTML = html;
                    })
                    .catch(error => {
                        console.error("페이지 로드 에러:", error);
                        mainContent.innerHTML = "<p>콘텐츠를 로드할 수 없습니다.</p>";
                    });
            }
        });
    });

    // URL에 특정 해시값(#myCoupons)이 있는 경우 자동 클릭
    const hash = window.location.hash;
    if (hash === "#myCoupons") {
        const myMessagesLink = document.querySelector('a[data-page="myMessages.php"]');
        if (myMessagesLink) {
            myMessagesLink.click();
        }
    }
});
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/footer.php');
?>