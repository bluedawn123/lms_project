<?php
//토큰을 파라미터로 사용하자
$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/database.php";

//해당 토큰을 갖고 있는 계정을 수정해야 하므로..
$sql = "SELECT * FROM membersKakao  
        WHERE account_activation_token = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $token_hash);  // token_hash가 스트링이고 1개이므로 s하나만
$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("토큰을 찾기 못했습니다.");
}

$sql = "UPDATE membersKakao
        SET account_activation_token = NULL
        WHERE memEmail = ?";   //★

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $user["memEmail"]);

$stmt->execute();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Account Activated</title>
    <meta charset="UTF-8">
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
    <script>
        let countdown = 5; // 초기 카운트다운 시간
        window.onload = function() {
            const countdownElement = document.getElementById('countdown');
            const interval = setInterval(function() {
                countdown--;
                countdownElement.textContent = countdown + "초 후 로그인 페이지로 이동합니다...";
                if (countdown <= 0) {
                    clearInterval(interval);
                    window.location.href = "loginTest2.php"; // 로그인 페이지로 리디렉션
                }
            }, 1000); // 1초마다 실행
        };
    </script>
</head>
<body>
    <div class="message-box">
        <h1>계정이 활성화되었습니다.</h1>
        <p>계정이 성공적으로 활성화되었습니다. 로그인하실 수 있습니다.</p>
        <div class="spinner"></div>
        <p id="countdown">5초 후 로그인 페이지로 이동합니다...</p>
    </div>
</body>
</html>