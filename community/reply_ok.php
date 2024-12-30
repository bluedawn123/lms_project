<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php'); // DB 연결 파일

header('Content-Type: application/json');

// 로그인 여부 확인
if (!isset($_SESSION['MemEmail'])) {
    echo json_encode(['status' => 'error', 'message' => '로그인이 필요합니다.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['MemEmail'];
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';

    if ($post_id <= 0 || empty($content)) {
        echo json_encode(['status' => 'error', 'message' => '모든 필드를 입력해주세요.']);
        exit;
    }

    $sql = "INSERT INTO comments (post_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => '쿼리 준비 실패: ' . $mysqli->error]);
        exit;
    }

    $stmt->bind_param("iss", $post_id, $user_id, $content);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => '댓글이 성공적으로 등록되었습니다.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => '댓글 등록 실패: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => '잘못된 요청입니다.']);
}

$mysqli->close();
?>