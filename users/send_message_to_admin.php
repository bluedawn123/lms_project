<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST 데이터 받기
    $sender_idx = $_POST['sender_idx']; // 보내는 사람 ID
    $receiver_tid = $_POST['receiver_tid']; // 받는 사람 ID
    $message = $_POST['message']; // 쪽지 내용
    $sender_name = $_POST['sender_name']; // 보내는 사람 이름
    $receiver_name = $_POST['receiver_name']; // 받는 사람 이름

    // 관리자 여부 확인 (예: 관리자의 ID를 1로 설정했다고 가정)
    $is_admin = ($sender_idx === 4); // 관리자의 고유 ID 확인

    // 관리자가 보내는 쪽지
    if ($is_admin) {
        $sql = "INSERT INTO toadminmessages (sender_id, receiver_id, message_content, sender_name, receiver_name, sent_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
    } else { // 일반 사용자가 보내는 쪽지
        $sql = "INSERT INTO tomembermessages (sender_id, receiver_id, message_content, sender_name, receiver_name, sent_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
    }

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iisss", $sender_idx, $receiver_tid, $message, $sender_name, $receiver_name);

    // 실행 결과 처리
    if ($stmt->execute() === true) {
        echo json_encode(['status' => 'success', 'message' => '쪽지를 성공적으로 보냈습니다.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => '쪽지 전송 중 오류가 발생했습니다.', 'error' => $stmt->error]);
    }

    $stmt->close();
    $mysqli->close();
    exit;
}

// POST 요청이 아닌 경우
http_response_code(405);
echo json_encode(['status' => 'error', 'message' => '잘못된 요청입니다.']);
exit;
?>