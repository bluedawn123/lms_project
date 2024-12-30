<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');


//있는지 확인
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 콘솔 또는 로그에서 확인
    error_log(print_r($_POST, true));

    // 클라이언트에 JSON으로 응답
    header('Content-Type: application/json');
    //echo json_encode($_POST);  //만약, 된다면 입력한 데이터가 잘 출력이 되야함. 잘된다


    // // POST 데이터 받기
    $sender_idx = $_POST['sender_idx']; // 클라이언트에서 보낸 sender_idx
    $receiver_tid = $_POST['receiver_tid']; // 클라이언트에서 보낸 receiver_tid
    $message = $_POST['message']; // 클라이언트에서 보낸 message
    $sender_name = $_POST['sender_name']; 
    $receiver_name = $_POST['receiver_name']; 

    // 메시지 저장 쿼리 실행
    $sql = "INSERT INTO toadminmessages (sender_id, receiver_id, message_content, sender_name, receiver_name, sent_at) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $mysqli->prepare($sql);

    // 데이터 타입과 매개변수 수 확인
    $stmt->bind_param("iisss", $sender_idx, $receiver_tid, $message, $sender_name, $receiver_name);

    if ($stmt->execute() === true) {
        $result = array('status'=>'success', 'message' => '쪽지를 성공적으로 보냈습니다.');
        echo json_encode($result);
    } else {
        $result = array('status'=>'error', 'message' => '쪽지 전송 중 오류가 발생했습니다.');
        echo json_encode($result);        
    }

    
    // $result = array('sender_idx'=>$_POST['sender_idx'], 'receiver_mid'=>$_POST['receiver_mid'],'message'=>$_POST['message'], 'sql'=>$sql);
    // echo json_encode($result); 
    exit;
}
$stmt->close();
$mysqli->close();
?>