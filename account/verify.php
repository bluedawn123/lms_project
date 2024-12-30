<?php
// 데이터베이스 연결
$mysqli = new mysqli("localhost", "quantumcode", "12345", "quantumcode");

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // 토큰 확인
    $stmt = $mysqli->prepare("SELECT * FROM memberskakao WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // 인증 상태 업데이트
        $stmt = $mysqli->prepare("UPDATE memberskakao SET is_verified = 1, is_email_verified = 1, token = NULL WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        echo "이메일 인증이 완료되었습니다. 이제 로그인할 수 있습니다.";
    } else {
        echo "유효하지 않은 토큰입니다.";
    }
} else {
    echo "잘못된 요청입니다.";
}
?>