<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

// 세션에서 사용자 이메일 가져오기
if (!isset($_SESSION['MemEmail'])) {
    echo "<script>alert('로그인 후 이용해주세요'); location.href = '/qc/index.php';</script>";
    exit;
}

$userEmail = $_SESSION['MemEmail'];

// SQL 쿼리 준비
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
$stmt->bind_param("s", $userEmail); // 사용자 이메일 바인딩
$stmt->execute();

// 결과 가져오기
$result = $stmt->get_result();

// 데이터 저장
$lectures = [];
while ($row = $result->fetch_assoc()) {
    $lectures[] = $row;
}

// 스테이트먼트 닫기
$stmt->close();
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

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>나의 강의 및 수강현황</title>
  <!-- Bootstrap 및 스타일 -->
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"> -->

</head>
<body>
  <div class="container mt-4">
    <h2 class="mb-4">나의 강의 및 수강현황</h2>
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
                <p class="mb-3"><strong>강의명 :</strong><?= htmlspecialchars($lecture['lecture_summary']); ?></p>
                <p class="text-muted"><strong>난이도 :</strong> <?= htmlspecialchars($lecture['difficulty']); ?></p>
                <hr>
                <p><strong>주문일:</strong> <?= htmlspecialchars($lecture['order_date']); ?></p>
                <p><strong>만료일:</strong> 
                  <span class="<?= strtotime($lecture['lecture_expiration']) < time() ? 'text-danger' : 'text-success'; ?>">
                    <?= htmlspecialchars($lecture['lecture_expiration'] ?? '없음'); ?>
                  </span>
                  <?php if ($remainingDays !== null && $remainingDays <= 90): ?>
                    <i class="fas fa-exclamation-triangle text-warning ms-2" title="만료 임박"></i>
                  <?php endif; ?>
                </p>
                <p><strong>진도율:</strong> <?= $progress; ?>%</p>
                <div class="progress mb-2">
                  <div class="progress-bar bg-success" role="progressbar" style="width: <?= $progress; ?>%;" 
                       aria-valuenow="<?= $progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
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
        <p>내 강의 정보가 없습니다.</p>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
