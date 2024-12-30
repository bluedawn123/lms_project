<?php
session_start();
session_unset();        // 모든 세션 변수의 등록 해지
session_destroy();      // 세션 아이디의 삭제
echo "<script>
  alert('로그아웃 되었습니다');
  location.href='login.php';
</script>";
?>