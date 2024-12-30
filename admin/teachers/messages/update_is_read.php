<?php
// 데이터베이스 연결
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/teachers/inc/dbcon.php');

// JSON 요청 처리
$data = json_decode(file_get_contents("php://input"), true);
$messageId = intval($data['id'] ?? 0);

if ($messageId > 0) {
    // 쿼리 실행
    $sql = "UPDATE toteachermessages SET is_read = 1 WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $messageId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'is_read 업데이트 성공']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'is_read 업데이트 실패']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => '잘못된 요청입니다.']);
}

$mysqli->close();
?>
