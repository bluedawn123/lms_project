<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/common.php');

$id = isset($_SESSION['TUID']) ? $_SESSION['TUID'] : null;
if (!isset($id)) {
  echo "
    <script>
      alert('강사로 로그인해주세요');
      location.href = '../login.php';
    </script>
  ";
}


$lecture_title = $_POST['title'] ?? '';
$lecture_platforms = $_POST['platforms'] ?? '';
$lecture_development = $_POST['development'] ?? '';
$lecture_technologies = $_POST['technologies'] ?? '';
$lecture_tuition = is_numeric($_POST['tuition'] ?? 0) ? (float)$_POST['tuition'] : 0;
$lecture_disTuition = is_numeric($_POST['dis_tuition'] ?? 0) ? (float)$_POST['dis_tuition'] : 0;
$lecture_registDay = $_POST['regist_day'] ?? 0;
$lecture_difficult = isset($_POST['difficult']) && $_POST['difficult'] !== ''
  ? $_POST['difficult']
  : 0; // 기본값
$lecture_ispremium = isset($_POST['ispremium']) ? 1 : 0;
$lecture_ispopular = isset($_POST['ispopular']) ? 1 : 0;
$lecture_isrecom = isset($_POST['isrecom']) ? 1 : 0;
$lecture_isfree = isset($_POST['isfree']) ? 1 : 0;
$lecture_subTitle = $_POST['sub_title'] ?? '';
$lecture_desc = rawurldecode($_POST['lecture_description'] ?? '');

$lecture_objectives = $_POST['objectives'] ?? '';
$lecture_tag = $_POST['tag'] ?? '';

$lecture_coverImage = $_FILES['cover_image'] ?? '';
$lecture_prVideo = $_FILES['pr_video'] ?? '';
// $lecture_prVideoUrl = $_POST['pr_videoUrl'] ?? '';
// $lecture_addVideosUrl = $_FILES['add_videosUrl'];


$lecture_videoId = $_POST['lecture_video'] ?? '';  //추가이미지의 imgid들 11,12,
$lecture_videoId = rtrim($lecture_videoId, ','); //추가이미지의 imgid들 11,12


$expiration_day = isset($lecture_registDay) ? date("Y-m-d", strtotime("+3 months", strtotime($lecture_registDay))) : '';

$lecture_cate = $lecture_platforms . $lecture_development . $lecture_technologies;

$lcid = '';
if (isset($lecture_platforms) && isset($lecture_development) && isset($lecture_technologies)) {
  $lcid_sql = "SELECT lcid FROM lecture_category WHERE ppcode = '$lecture_platforms' AND pcode = '$lecture_development' AND code = '$lecture_technologies'";
  if ($lcid_result = $mysqli->query($lcid_sql)) {
    $lcid_data = $lcid_result->fetch_object();
    $lcid = $lcid_data->lcid;
    //print_r($lcid);
  }
}


if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
  $fileUploadResult = fileUpload($_FILES['cover_image'], 'image');
  if ($fileUploadResult) {
    $lecture_coverImage = $fileUploadResult;
  } else {
    echo "<script>
              alert('파일 첨부할 수 없습니다.');
              history.back();
          </script>";
  }
}

if (isset($_FILES['pr_video']) && $_FILES['pr_video']['error'] == UPLOAD_ERR_OK) {
  $fileUploadResult = fileUpload($_FILES['pr_video'], 'video');
  if ($fileUploadResult) {
    $lecture_prVideo =  $fileUploadResult ?: '';
  } else {
    echo "<script>
              alert('파일 첨부할 수 없습니다.');
              history.back();
          </script>";
  }
}


$sql = "INSERT INTO  lecture_list
    (category, lcid, title, cover_image, t_id, isfree, ispremium, ispopular, isrecom, tuition, dis_tuition, regist_day, expiration_day, sub_title, description, learning_obj, difficult, lecture_tag, pr_video )
    VALUES
    ('$lecture_cate', '$lcid' ,'$lecture_title', '$lecture_coverImage', '$id', $lecture_isfree, $lecture_ispremium, $lecture_ispopular, $lecture_isrecom, $lecture_tuition, $lecture_disTuition, '$lecture_registDay', '$expiration_day', '$lecture_subTitle', '$lecture_desc', '$lecture_objectives', $lecture_difficult, '$lecture_tag', '$lecture_prVideo')
    ";
echo $sql;
$lecture_result = $mysqli->query($sql);
if (!$lecture_result) {
  echo "SQL Error: " . $mysqli->error;
}
$lid = $mysqli->insert_id;

if ($lecture_result) { //상품이 products테이블에 등록되면
  //추가 이미지 등록
  if ($lecture_videoId) {
    //테이블 product_image_table에서 imgid의 값이 11,12인 데이터 행에서 pid 값을 $pid로 업데이트
    $update_sql = "UPDATE lecture_video SET lid=$lid WHERE lvid IN ($lecture_videoId)";
    $update_result = $mysqli->query($update_sql);
  }
}


if ($lecture_result) {
  echo "<script>
    alert('강의가 등록되었습니다.');
    location.href = 'lecture_list.php';
    </script>";
}
