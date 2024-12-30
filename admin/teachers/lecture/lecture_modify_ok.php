<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/common.php');

$id = isset($_SESSION['AUID']) ? $_SESSION['AUID']  : $_SESSION['TUID'];
if (!isset($id)) {
  echo "
    <script>
      alert('관리자로 로그인해주세요');
      location.href = '../login.php';
    </script>
  ";
}

var_dump($_POST);

$lid = $_POST['lid'];

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
$lecture_desc = rawurldecode($_POST['lecture_description']);

$lucture_objectives = $_POST['objectives'] ?? '';
$lecture_tag = $_POST['tag'] ?? '';

$lecture_coverImage = $_FILES['cover_image'] ?? null;
$lecture_prVideo = null;
$lecture_prVideoUrl = $_POST['pr_videoUrl'] ?? '';
// $lecture_addVideosUrl = $_FILES['add_videosUrl'];


$expiration_day = date("Y-m-d", strtotime("+3 months", strtotime($lecture_registDay)));

$lecture_cate = $lecture_platforms . $lecture_development . $lecture_technologies;


$lecture_videoId = $_POST['lecture_video'];  //추가이미지의 imgid들 11,12,
$lecture_videoId = rtrim($lecture_videoId, ','); //추가이미지의 imgid들 11,12

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
    $lecture_prVideo = $fileUploadResult;
  } else {
    echo "<script>
              alert('파일 첨부할 수 없습니다.');
              history.back();
          </script>";
  }
}

$sql = "UPDATE lecture_list SET 
  category = '$lecture_cate',
  title = '$lecture_title', 
  isfree = $lecture_isfree, 
  ispremium = $lecture_ispremium, 
  ispopular = $lecture_ispopular, 
  isrecom = $lecture_isrecom, 
  tuition = $lecture_tuition, 
  dis_tuition = $lecture_disTuition, 
  regist_day = '$lecture_registDay', 
  expiration_day = '$expiration_day', 
  sub_title = '$lecture_subTitle', 
  description = '$lecture_desc', 
  learning_obj = '$lucture_objectives', 
  difficult = '$lecture_difficult', 
  lecture_tag = '$lecture_tag'
  ";


if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
  $sql .= ", cover_image = '$lecture_coverImage'";
}
if (isset($_FILES['pr_video']) && $_FILES['pr_video']['error'] == UPLOAD_ERR_OK) {
  $sql .= ", pr_video = '$lecture_prVideo'";
}

$sql .= " WHERE lid = $lid";
$result = $mysqli->query($sql);

if ($result) { //상품이 products테이블에 등록되면

  //추가 이미지가 변동되면
  if ($lecture_videoId) {
    //테이블 product_image_table에서 imgid의 값이 11,12인 데이터 행에서 pid 값을 $pid로 업데이트
    $update_sql = "UPDATE lecture_video SET lid=$lid WHERE lvid IN ($lecture_videoId)";
    $update_result = $mysqli->query($update_sql);
  }
}
/*
$sql = "INSERT INTO  lecture_list
    (category, title, cover_image, t_id, isfree, ispremium, ispopular, isrecom, tuition, dis_tuition, regist_day, expiration_day, sub_title, description, learning_obj, difficult, lecture_tag, pr_video )
    VALUES
    ('$lecture_cate', '$lecture_title', '$lecture_coverImage', '{$_SESSION['AUID']}', $lecture_isfree, $lecture_ispremium, $lecture_ispopular, $lecture_isrecom, $lecture_tuition, $lecture_disTuition, '$lecture_registDay', '$expiration_day', '$lecture_subTitle', '$lecture_desc', '$lucture_objectives', $lecture_difficult, '$lecture_tag', '$lecture_prVideo')
    ";

$lecture_result = $mysqli->query($sql);
*/
if ($result) {
  echo
  "<script>
    alert('강의가 수정되었습니다.');
    location.href = 'lecture_list.php';
    </script>";
}
