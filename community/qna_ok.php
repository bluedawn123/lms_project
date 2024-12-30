<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php'); // DB 연결 파일

header('Content-Type: application/json');

// 로그인 여부 확인
if (!isset($_SESSION['MemEmail'])) {
    echo json_encode([
        'status' => 'error',
        'message' => '로그인이 필요합니다.'
    ]);
    exit;
}

// 요청 데이터 확인
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['MemEmail'];
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';

    // 입력 값 검증
    if (empty($title) || empty($content)) {
        echo json_encode([
            'status' => 'error',
            'message' => '모든 필드를 입력해주세요.'
        ]);
        exit;
    }

    // 게시물 데이터 삽입
    $sql = "INSERT INTO board (category, title, content, user_id, date) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        echo json_encode([
            'status' => 'error',
            'message' => '쿼리 준비 실패: ' . $mysqli->error
        ]);
        exit;
    }

    $category = 'qna';
    $stmt->bind_param("ssss", $category, $title, $content, $user_id);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => '문의가 성공적으로 등록되었습니다.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => '문의 등록 실패: ' . $stmt->error
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => '잘못된 요청입니다.'
    ]);
}

$mysqli->close();
?>
