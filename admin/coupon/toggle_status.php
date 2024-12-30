<?php
header('Content-Type: application/json');

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

// 요청 데이터 가져오기
$data = json_decode(file_get_contents('php://input'), true);

// 데이터 검증
if (!isset($data['cid']) || !isset($data['status'])) {
    echo json_encode(['success' => false, 'message' => '유효하지 않은 요청 데이터']);
    exit;
}

$cid = intval($data['cid']);
$status = intval($data['status']);

// 상태 업데이트 SQL 실행
$sql = "UPDATE coupons SET status = ? WHERE cid = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ii', $status, $cid);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => '상태 업데이트 실패']);
}
?>
