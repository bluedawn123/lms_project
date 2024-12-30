<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

// 사용자 로그인 확인
if (!isset($_SESSION['MemEmail'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href = '/qc/index.php';</script>";
    exit;
}

// 현재 로그인한 사용자 ID 가져오기
$userEmail = $_SESSION['MemEmail'];

// 사용자가 작성한 글 가져오기
$sql = "SELECT pid, title, content, date, category, hit, likes 
        FROM board 
        WHERE user_id = ? 
        ORDER BY date DESC";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

// 데이터 저장
$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>내 작성글</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <style>
        .post-list {
            max-width: 800px;
            margin: 20px auto;
        }
        .post-item {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #fff;
        }
        .post-item .title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #007bff;
        }
        .post-item .meta {
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="post-list">
            <h2 class="mb-4">내 작성글</h2>
            <?php if (empty($posts)): ?>
                <p class="text-center text-muted">작성한 글이 없습니다.</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post-item">
                        <div class="title"><?= htmlspecialchars($post['title']); ?></div>
                        <div class="meta">
                            <span>카테고리: <?= htmlspecialchars($post['category']); ?></span> | 
                            <span>작성일: <?= htmlspecialchars($post['date']); ?></span> | 
                            <span>조회수: <?= htmlspecialchars($post['hit']); ?></span> | 
                            <span>좋아요: <?= htmlspecialchars($post['likes']); ?></span>
                        </div>
                        <div class="content mt-2"><?= htmlspecialchars(mb_strimwidth($post['content'], 0, 100, '...')); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
