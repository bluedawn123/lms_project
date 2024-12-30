<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php'); // DB 연결 파일

header('Content-Type: application/json');

// 요청 데이터 확인
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($post_id <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => '유효하지 않은 게시물 ID입니다.'
        ]);
        exit;
    }

    // 조회수 증가 쿼리
    $sql = "UPDATE board SET hit = hit + 1 WHERE pid = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        echo json_encode([
            'status' => 'error',
            'message' => '쿼리 준비 실패: ' . $mysqli->error
        ]);
        exit;
    }

    $stmt->bind_param("i", $post_id);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => '조회수가 증가되었습니다.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => '조회수 증가 실패: ' . $stmt->error
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