<?php
session_start(); // 세션 시작
include_once("../admin/inc/dbcon.php"); // DB 연결


echo print_r($_SESSION, true);
//Array ( [nickname] => 김수경 [profile_nickname_needs_agreement] => [profile] => Array ( [nickname] => 김수경 [is_default_nickname] => ) [has_email] => 1 [email_needs_agreement] => [is_email_valid] => 1 [is_email_verified] => 1 [email] => sukyuk2@naver.com [no] => 0 [yes] => 11111111 )
echo "<hr/>";

echo var_dump($_SESSION);
echo "<hr/>";

//echo "<hr/>";
// echo print_r($_SESSION['properties']);
//echo "<hr/>";
// echo print_r($_SESSION['kakao_account']);
echo "<hr/>";

// 배열내 특정 값 뽑아내는 방법
if (isset($_SESSION['kakao_account']) && isset($_SESSION['kakao_account']['email'])) {
    echo $_SESSION['kakao_account']['email'];
} else {
    echo "kakao_account 또는 email 값이 존재하지 않습니다.";
}

echo "<hr/>";

if (isset($_SESSION['nickname'])) {
  echo "환영합니다." . $_SESSION['nickname'] ."회원님";
} else {
  echo "nickname 값이 존재하지 않습니다.";
}

$sql = "SELECT * FROM memberskakao";
$data = $mysqli->query($sql);
//$result = $data->fetch_object() ....은 $result -> 컬럼값 으로 개체 값 출력

// 데이터를 객체로 가져오기
while ($result = $data->fetch_object()) {
    // 1. 전체 객체 출력
    echo "<pre>";
    print_r($result);
    echo "</pre>";

    // 2. 객체의 특정 속성 접근
    echo "ID: " . $result->memId . "<br>";
    echo "Name: " . $result->memName . "<br>";
    echo "Email: " . $result->memEmail . "<br>";
    echo "Grade: " . $result->grade . "<br>";
    echo "<hr>";
}

// 연결 종료
$mysqli->close();
?>