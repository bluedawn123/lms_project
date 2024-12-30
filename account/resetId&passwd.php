<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>비밀번호 재설정</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .reset-container {
            max-width: 455px;
            margin: 80px auto;
            padding: 65px 35px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-reset {
            background-color: #007bff;
            color: #fff;
            border: none;
            font-weight: bold;
        }
        .btn-reset:hover {
            background-color: #0056b3;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="reset-container text-center">
            <!-- Logo -->
            <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/index2.php">
             <img src="../img/main_logo1.png" alt="Logo" class="mb-4">
            </a>

            <!-- Title -->
            <h4 class="mb-3">비밀번호를 재설정하세요</h4>
            <p class="mb-2" style="font-size: 14px;">이메일을 입력하면 비밀번호 재설정 링크를 보내드립니다.</p>
            <p class="mb-4" style="font-size: 12px;">카카오톡 로그인의 경우 해당 주소에서 변경해주십시오.</p>

            <!-- Reset Password Form -->
            <form action="send_password_reset.php" method="POST">
                <div class="form-group mb-3">
                    <input type="email" class="form-control" name="email" id="email" placeholder="이메일" required>
                </div>
                <button type="submit" class="btn btn-reset w-100 mb-3">재설정 링크 보내기</button>
            </form>

            <!-- Back to Login -->
            <a href="loginTest2.php" class="btn btn-light w-100">로그인 페이지로 돌아가기</a>

            <!-- Footer -->
            <p class="mt-5" style="font-size: 12px;">혹시 이메일이 기억나지 않거나 재설정 링크를 받지 못하셨나요?</p>
            <p class="mt-1" style="font-size: 12px;"><a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/account/service.php" 
            class="text-decoration-none">고객 지원에 문의하기</a></p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>