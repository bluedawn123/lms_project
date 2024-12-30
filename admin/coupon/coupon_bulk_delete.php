<?php
header('Content-Type: application/json'); // JSON 형식으로 응답

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['ids'])) {
    error_log("유효하지 않은 요청 데이터: " . json_encode($data));
    echo json_encode(['success' => false, 'message' => '유효하지 않은 요청 데이터']);
    exit;
}

// 디버깅: 받은 ID 값 확인
$ids = $data['ids'];

// SQL 실행
$idList = implode(',', array_map('intval', $ids));
$sql = "DELETE FROM coupons WHERE cid IN ($idList)";
$result = $mysqli->query($sql);

if ($result) {
    error_log("삭제 성공: $idList");
    echo json_encode(['success' => true]);
} else {
    error_log("SQL 에러: " . $mysqli->error); // SQL 오류 기록
    echo json_encode(['success' => false, 'message' => '쿼리 실행 실패: ' . $mysqli->error]);
}
?>
