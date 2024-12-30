<?php
// ![수정필요] 카카오 API 환경설정 파일
include_once "./config.php";

// 카카오 로그인 URL 생성
$replace = array(
    '{client_id}' => $kakaoConfig['client_id'],
    '{redirect_uri}' => $kakaoConfig['redirect_uri'],
    '{state}' => md5(mt_rand(111111111, 999999999)),
);
setcookie('state', $replace['{state}'], time() + 300); // 300 초동안 유효
$login_auth_url = str_replace(array_keys($replace), array_values($replace), $kakaoConfig['login_auth_url']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인 페이지</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 455px;
            margin: 80px auto;
            padding: 65px 35px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .kakao-btn {
            background-color: #fee500;
            color: #3c1e1e;
            font-weight: bold;
            border: none;
        }
        .kakao-btn:hover {
            background-color: #ffd700;
        }
        .btn-login {
            background-color: #007bff;
            color: #fff;
            border: none;
            font-weight: bold;
        }
        .btn-login:hover {
            background-color: #0056b3;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container text-center">
            <!-- Logo -->
            <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/index2.php">
             <img src="../img/main_logo1.png" alt="Logo" class="mb-4">
            </a>

            <!-- Title -->
            <h4 class="mb-3">학습의 경계를 허물다,</h4>
            <h5 class="mb-4">퀀텀 코드</h5>

            <!-- Kakao Login -->
            <a href="<?php echo $login_auth_url ?>" class="btn kakao-btn w-100 mb-3">
                <img src="https://img.icons8.com/ios-filled/20/000000/chat--v1.png" alt="Kakao Icon" style="margin-right: 8px;">
                카카오로 1초 만에 시작하기
            </a>
            <!-- <button onclick="location.href='./logout.php'" class="btn btn-light w-100 mb-3">카카오 세션 종료</button> -->

            <hr class="my-4">

            <!-- Email Login Form -->
            <form action="login_ok.php" method="POST">
                <div class="form-group mb-3">
                    <input type="email" class="form-control" name="email" placeholder="이메일" required>
                </div>
                <div class="form-group mb-3 position-relative">
                    <input type="password" class="form-control" name="password" placeholder="비밀번호" required>
                </div>
                <input type="hidden" id="lastLoginAt" name="lastLoginAt">
                <button type="submit" class="btn btn-login w-100 mb-3">로그인</button>
            </form>

            <!-- Sign Up Button -->
            <a href="signup.php" class="btn btn-light w-100">이메일로 회원가입</a>

            <!-- Footer -->
            <p class="mt-5" style="font-size: 12px;">혹시 아이디 / 비밀번호가 기억이 안나신다면?</p>
            <p class="mt-1" style="font-size: 12px;"><a href="resetId&passwd.php" class="text-decoration-none">아이디 / 비밀번호 재설정하기</a></p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    //지금 시간을 디비에 저장하도록 설정.
    document.addEventListener('DOMContentLoaded', function () {
    const now = new Date();
    const formattedTime = now.toISOString().slice(0, 19).replace('T', ' '); // MySQL DATETIME 형식으로 변환
    document.getElementById('lastLoginAt').value = formattedTime; // 숨겨진 input에 시간 설정
    });

    </script>
</body>
</html>