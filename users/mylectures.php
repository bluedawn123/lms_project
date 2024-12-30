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
        l.lid AS lecture_id, -- 강의 고유번호
        l.title AS lecture_title,
        l.category AS lecture_category,
        l.cover_image AS lecture_image,
        l.t_id AS instructor_name,
        l.tuition AS original_price,
        l.dis_tuition AS discounted_price,
        l.sub_title AS lecture_summary,
        l.difficult AS difficulty,
        l.expiration_day AS lecture_expiration -- 만료일 추가
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
  <title>나의 강의 및 수강현황</title>
  <!-- Bootstrap 및 스타일 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <style>
    body {
      background-color: #f8f9fa;
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
      height: 200px;
      object-fit: cover;
    }
    .lecture-title {
      font-size: 1.25rem;
      font-weight: bold;
      color: #333;
    }
    .lecture-category {
      color: #6c757d;
      font-size: 0.9rem;
    }
    .price {
      color: #007bff;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container mt-4">
    <h2 class="mb-4">나의 강의 목록</h2>
    <div class="row">
      <?php if (!empty($lectures)): ?>
        <?php foreach ($lectures as $lecture): ?>
            <div class="col-md-4 mb-4">
  <div class="card lecture-card">
    <img src="<?= htmlspecialchars($lecture['lecture_image'] ?: '/qc/img/default-lecture.jpg'); ?>" class="card-img-top" alt="강의 이미지">
    <div class="card-body">
      <h5 class="lecture-title"><?= htmlspecialchars($lecture['lecture_title']); ?></h5>
      <p class="lecture-category"><?= htmlspecialchars($lecture['lecture_category']); ?></p>
      <p class="mb-2"><strong>강사:</strong> <?= htmlspecialchars($lecture['instructor_name']); ?></p>
      <p class="mb-3"><?= htmlspecialchars($lecture['lecture_summary']); ?></p>
      <p class="text-muted">난이도: <?= htmlspecialchars($lecture['difficulty']); ?></p>
      <hr>
      <p class="text-muted">
        <strong>만료일:</strong>
        <span class="<?= strtotime($lecture['lecture_expiration']) < time() ? 'text-danger' : 'text-success'; ?>">
          <?= htmlspecialchars($lecture['lecture_expiration'] ?? '없음'); ?>
        </span>
      </p>
      <a href="/qc/lecture/lecture_view.php?lid=<?= htmlspecialchars($lecture['lecture_id']); ?>" class="btn btn-primary w-100 mt-3">강의로 이동</a>
    </div>
  </div>
</div>

        <?php endforeach; ?>
      <?php else: ?>
        <p>주문한 강의가 없습니다.</p>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
