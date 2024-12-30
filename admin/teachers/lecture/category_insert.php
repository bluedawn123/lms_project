<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

$name = $_POST['name'];
$step = $_POST['step'];
$pcode = $_POST['pcode'] ?? '';
$ppcode = $_POST['ppcode'] ?? '';

$sql = "SELECT code FROM lecture_category WHERE step = $step AND (pcode = '$pcode' OR pcode IS NULL) ORDER BY code DESC LIMIT 1";
$result = $mysqli->query($sql);
$data = $result->fetch_object();

$code = '0001';

if ($data) {
  // 기존 code의 숫자 부분을 추출하고 1을 더함
  $numberPart = intval(substr($data->code, 1));
  $code = str_pad($numberPart + 1, 4, "0", STR_PAD_LEFT);
}

// step에 따라 prefix 추가
$prefix = chr(64 + $step); // A, B, C ...
$fixcode = $prefix . $code;

$cate_sql = "INSERT INTO  lecture_category ( code, pcode, ppcode, name, step ) VALUES ('$fixcode', '$pcode','$ppcode', '$name', $step)";
$cate_result = $mysqli->query($cate_sql);

if ($cate_result) {
  $r_data = array('result' => 1); //성공
  echo json_encode($r_data);
} else {
  $r_data = array('result' => 0); //실패
  echo json_encode($r_data);
}
