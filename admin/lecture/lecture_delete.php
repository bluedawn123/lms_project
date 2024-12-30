<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

$lid = $_GET['lid'];

$sql = "SELECT * FROM lecture_list WHERE lid = $lid";
if (!isset($lid)) {
  echo "<script>alert('상품정보가 없습니다.'); location.href = 'lecture_list.php';</script>";
}


$cover_sql = "SELECT cover_image FROM lecture_list WHERE lid = $lid";
$cover_result = $mysqli->query($cover_sql);
$cover_data = $cover_result->fetch_object();
$coverImage = $cover_data->cover_image;

unlink($_SERVER['DOCUMENT_ROOT'] . $coverImage);

$pr_sql = "SELECT pr_video FROM lecture_list WHERE lid = $lid";
if ($pr_result = $mysqli->query($pr_sql)) {

  $pr_data = $pr_result->fetch_object();
  $prVidio = $pr_data->pr_video ?? '';

  if (is_array($prVidio)) {
    $prVideo = '';
  }

  if (!empty($prVideo) && is_string($prVideo) && file_exists($_SERVER['DOCUMENT_ROOT'] . $prVideo)) {
    unlink($_SERVER['DOCUMENT_ROOT'] . $prVideo);
  }
}

$del_sql = "DELETE FROM lecture_list WHERE lid = $lid";
$del_result = $mysqli->query($del_sql);


$addvideo_sql = "SELECT video_lecture FROM lecture_video WHERE lid = $lid";
$addvideo_result = $mysqli->query($addvideo_sql);
$addVideos = [];

while ($addvideo_data = $addvideo_result->fetch_object()) {
  $addVideos[] = $addvideo_data->video_lecture;
}

foreach ($addVideos as $addVideo) {
  unlink($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/upload/' . $addVideo);
}

$addvideo_del_sql = "DELETE FROM lecture_video WHERE lid = $lid";
$addvideo_del_result = $mysqli->query($addvideo_del_sql);

if ($del_result) {
  echo "<script>
    alert('강의가 삭제되었습니다.');
    location.href = 'lecture_listView.php';
    </script>";
}
