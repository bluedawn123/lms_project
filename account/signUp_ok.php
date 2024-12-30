<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$number = $_POST['number'] ?? '';
$password = $_POST['password'];
$password = hash('sha512', $password);
$activation_token = bin2hex(random_bytes(16));
$activation_token_hash = hash("sha256", $activation_token);

// SQL 쿼리에서 memCreatedAt 포함
$sql = "INSERT INTO membersKakao
(memName, memPassword, memEmail, number, memCreatedAt, account_activation_token)
VALUES
(?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->stmt_init();

if (!$stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

// memCreatedAt에 현재 타임스탬프 제공
$memCreatedAt = date("Y-m-d H:i:s");

$stmt->bind_param("ssssss", $name, $password, $email, $number, $memCreatedAt, $activation_token_hash);

if ($stmt->execute()) {
    $mail = require __DIR__ . "/mailer.php";

    $mail->setFrom("haemilyjh@naver.com"); // 발신 이메일
    $mail->addAddress($_POST["email"]);
    $mail->Subject = "Account Activation";
    $mail->CharSet = 'UTF-8'; // 문자 인코딩 설정
    $mail->isHTML(true);      // HTML 메일 설정
    $mail->Body = <<<END
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <p>아래 링크를 클릭하여 계정을 활성화하세요:</p>
    <a href="http://localhost/qc/account/active_account.php?token=$activation_token">여기를 클릭해주세요</a>
</body>
</html>
END;

    try {
        $mail->send();
    } catch (Exception $e) {
        echo "메세지가 보내지지 않았습니다.. Mailer error: {$mail->ErrorInfo}";
        exit();
    }

    echo "<!DOCTYPE html>
<html lang='ko'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>저장 성공</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .message-box {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .message-box h1 {
            color: #28a745;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .message-box p {
            color: #6c757d;
            font-size: 18px;
        }
        .spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            margin-top: 15px;
            border: 4px solid #e9ecef;
            border-radius: 50%;
            border-top-color: #28a745;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <div class='message-box'>
        <h1>인증 이메일 발송 성공!</h1>
        <p>해당 이메일에서 인증을 완료해 주세요!</p>
        <div class='spinner'></div>
        <p id='countdown'>5초후 로그인 페이지로 이동합니다...</p>
    </div>
    <script>
        let countdown = 5; // 초기 카운트다운 시간
        const countdownElement = document.getElementById('countdown');
        const interval = setInterval(function() {
            countdown--;
            countdownElement.textContent = countdown + '초후 로그인 페이지로 이동합니다...';
            if (countdown <= 0) {
                clearInterval(interval);
                window.location.href = '/qc/account/loginTest2.php'; //로그인페이지로 리디렉션
            }
        }, 1000); // 1초마다 실행
    </script>
</body>
</html>";

    exit(); // 리다이렉션 후 추가 코드 실행 방지
} else {
    echo "이메일이 데이터베이스에 존재하지 않습니다.";
}

$stmt->close();
$mysqli->close();
?>
