<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

// 세션에서 사용자 이메일 가져오기
if (!isset($_SESSION['MemEmail'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href = '/qc/index.php';</script>";
    exit;
}

$userEmail = $_SESSION['MemEmail'];

// SQL 쿼리 준비 (회원이 주문한 강의 정보 가져오기)
$sql = "
    SELECT 
        o.odid AS order_id,
        o.total_price,
        o.status AS order_status,
        o.createdate AS order_date,
        l.title AS lecture_title,
        l.category AS lecture_category,
        l.t_id AS instructor_name,
        l.tuition AS original_price,
        l.dis_tuition AS discounted_price,
        l.sub_title AS lecture_summary,
        l.difficult AS difficulty
    FROM 
        lecture_order AS o
    JOIN 
        lecture_list AS l 
    ON 
        o.lid = l.lid
    WHERE 
        o.mid = ?
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

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>내 강의 주문 정보</title>
  <!-- 캐싱 문제 방지 -->
  <link rel="stylesheet" href="/qc/css/core-style.css?v=<?= time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> -->
  <style>
    .profile-img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid #ccc;
    }
    p {
      margin-top: 0;
      margin-bottom: 0;
    }
  </style>
</head>
<body>
  <div class="container mt-2">
    <h3>나의 강의 주문 정보</h3>
    <?php if (!empty($lectures)): ?>
      <div class="row mt-4">
        <!-- 강의 주문 정보 출력 -->
        <?php foreach ($lectures as $lecture): ?>
          <div class="col-md-6 mb-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">강의 제목: <?= htmlspecialchars($lecture['lecture_title']); ?></h5>
                <p class="card-text"><strong>카테고리:</strong> <?= htmlspecialchars($lecture['lecture_category']); ?></p>
                <p class="card-text"><strong>강사:</strong> <?= htmlspecialchars($lecture['instructor_name']); ?></p>
                <p class="card-text"><strong>난이도:</strong> <?= htmlspecialchars($lecture['difficulty']); ?></p>
                <p class="card-text"><strong>강의 요약:</strong> <?= htmlspecialchars($lecture['lecture_summary']); ?></p>
                <p class="card-text"><strong>가격:</strong> <?= number_format($lecture['original_price']); ?>원</p>
                <hr>
                <p class="card-text"><strong>주문 ID:</strong> <?= htmlspecialchars($lecture['order_id']); ?></p>
                <p class="card-text"><strong>실제 결제 금액:</strong> <?= number_format($lecture['total_price']); ?>원</p>
                <p class="card-text"><strong>주문 상태:</strong> <?= $lecture['order_status'] == 1 ? '완료' : '대기'; ?></p>
                <p class="card-text"><strong>주문 날짜:</strong> <?= htmlspecialchars($lecture['order_date']); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>주문한 강의가 없습니다.</p>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>