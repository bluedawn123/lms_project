<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/dbcon.php');

$cate = $_POST['cate']; //A0001
$step = $_POST['step'];
$ppcode = $_POST['ppcode'] ?? '';

$html = '';

if(isset($ppcode)){
  $sql = "SELECT * FROM lecture_category WHERE step = $step AND pcode = '$cate' AND ppcode = '$ppcode'";
}else{
  $sql = "SELECT * FROM lecture_category WHERE step = $step AND pcode = '$cate'";
}
$result = $mysqli->query($sql);

while($data = $result->fetch_object()){ //조회된 값들 마다 할일, 값이 있으면 $data할당
    $html .= "<option value=\"{$data->code}\">{$data->name}</option>";
}

echo $html;
$mysqli->close();
?>