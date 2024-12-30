<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Composer autoload 파일 로드
require '../vendor/autoload.php';

// 데이터베이스 연결
$mysqli = new mysqli("localhost", "quantumcode", "12345", "quantumcode");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $memName = $_POST['memName'];
    $memEmail = $_POST['memEmail'];
    $memPassword = password_hash($_POST['memPassword'], PASSWORD_BCRYPT); // 비밀번호 암호화
    $memAddr = $_POST['memAddr'] ?? null; // 주소 (선택 사항)
    $token = bin2hex(random_bytes(16)); // 인증 토큰 생성

    // 이메일 중복 확인
    $stmt = $mysqli->prepare("SELECT * FROM memberskakao WHERE memEmail = ?");
    $stmt->bind_param("s", $memEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "이미 등록된 이메일입니다.";
        exit();
    }

    // 회원 정보 삽입
    $stmt = $mysqli->prepare("INSERT INTO memberskakao (memName, memPassword, memEmail, memAddr, token, is_verified, grade) VALUES (?, ?, ?, ?, ?, 0, 'bronze')");
    $stmt->bind_param("sssss", $memName, $memPassword, $memEmail, $memAddr, $token);

    if ($stmt->execute()) {
        // 이메일 전송
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.naver.com'; // 구글 SMTP 서버
            $mail->SMTPAuth = true;
            $mail->Username = 'haemilyjh@gmail.com'; // 구글 사용자명
            $mail->Password = 'dkskWP12!@'; // 구글 앱 비밀번호
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('haemilyjh@gmail.com', 'Your App'); // 발신자 이메일
            $mail->addAddress($memEmail);

            // 인증 링크 생성
            $verificationLink = "http://yourdomain.com/verify.php?token=$token";

            $mail->isHTML(true);
            $mail->Subject = '회원가입 인증';
            $mail->Body = "안녕하세요, <br> 회원가입을 완료하려면 <a href='$verificationLink'>여기를 클릭</a>하세요.";
            $mail->AltBody = "회원가입을 완료하려면 다음 링크를 클릭하세요: $verificationLink";

            $mail->send();
            echo "인증 이메일이 발송되었습니다.";
        } catch (Exception $e) {
            echo "이메일 전송 실패: {$mail->ErrorInfo}";
        }
    } else {
        echo "회원가입 실패: " . $stmt->error;
    }
}
?>