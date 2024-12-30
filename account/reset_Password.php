<?php
//토큰을 파라미터로 사용하자
$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/database.php";

//해당 토큰을 갖고 있는 계정을 수정해야 하므로..
$sql = "SELECT * FROM membersKakao  
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $token_hash);  //token_hash가 스트링이고 1개이므로 s하나만
$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("토큰이 없습니다. 다시 확인해주세요");
}
if (strtotime($user["reset_token_expires_at"]) <= time()) { //토큰 만기시간
    die("기간이 만기하셨습니다. 다시 확인해주세요");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>비밀번호 재설정</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>

<style>
    .reset-password-container {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    font-family: 'Arial', sans-serif;
}

.reset-password-container h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

.reset-password-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
}

.form-group input {
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.btn-submit {
    background-color: #4CAF50;
    color: white;
    font-size: 16px;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-submit:hover {
    background-color: #45a049;
}
.reset-password-container p {
        margin: 0; /* 상하 간격 제거 */
        font-size: 13px; /* 글씨 크기 10px로 설정 */
    }
</style>


<body>

<div class="reset-password-container">
    <h2>비밀번호를 재설정 하세요.</h2>

    <form method="post" action="process_reset_password.php" class="reset-password-form" id="resetPasswordForm">
        <input type="hidden" name="token" id="token" value="<?= htmlspecialchars($token) ?>">

        <div class="form-group">
            <label for="password">새 비밀번호</label>
            <input type="password" id="password" name="password" placeholder="새 비밀번호를 입력하세요" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">비밀번호 확인</label>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="비밀번호를 다시 입력하세요" required>
            <p id="error-message" style="color: red; font-size: 14px; display: none;">두 비밀번호가 일치하지 않습니다.</p>
        </div>
        <p>비밀번호는 8자 이상이어야 합니다.</p>
        <P>비밀번호는 문자 한 개 이상을 포함해야 합니다.</P>
        <p>비밀번호는 숫자 한 개 이상을 포함해야 합니다.</p>

        <button type="submit" class="btn-submit">저장하기</button>
    </form>
</div>

<script>
    document.getElementById('resetPasswordForm').addEventListener('submit', function (e) {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        const errorMessage = document.getElementById('error-message');

        // 비밀번호가 일치하지 않는 경우
        if (password !== passwordConfirmation) {
            e.preventDefault(); // 폼 제출 방지
            errorMessage.style.display = 'block'; // 에러 메시지 표시
        } else {
            errorMessage.style.display = 'none'; // 에러 메시지 숨김
        }
    });
</script>

</body>
</html>