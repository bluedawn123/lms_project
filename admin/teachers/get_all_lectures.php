<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');


// `tid`가 전달되었는지 확인
if (!isset($_GET['tid'])) {
    echo json_encode(['error' => 'No teacher ID provided']);
    exit;
}
$tid = (int) $_GET['tid']; // tid를 정수로 변환
//필요한가...?
$sql = "SELECT * FROM teachers WHERE tid = $tid";  //여기서 tid는 숫자.
$result = $mysqli->query($sql); //쿼리 실행 결과
$data = $result->fetch_object();


// SQL 쿼리 실행


$sql2 = "SELECT * FROM lecture_list WHERE t_id ='$data->id'";
$result2 = $mysqli->query($sql2);


if (!$result2) {
    echo json_encode(['error' => 'Database query failed: ' . $mysqli->error]);
    exit;
}


// 데이터를 JSON 형식으로 변환
$lectures = [];
while ($row = $result2->fetch_object()) {
    $lectures[] = $row;
}
$result = array('result'=>$lectures);
echo json_encode($result);
exit;

?>