<?php
session_start();
print_r($_SESSION); 
if (!isset($title)) {
  $title = '';
}
isset($coupon_css) ? $coupon_css : '';
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title> <?= $title; ?> - quantumcode</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/css/common.css">
    <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/css/core-style.css">
    <!-- Favicon 기본 설정 -->
    <link rel="apple-touch-icon" sizes="57x57" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <?php
    if (isset($summernote_css)) {
      echo $summernote_css;
    }
    if (isset($lecture_css)) {
      echo $lecture_css;
    }
    if (isset($teacher_css)) {
      echo $teacher_css;
    }
    if (isset($member_css)) {
      echo $member_css;
    }
    if(isset($board_css)){
      echo $board_css;
    }
    ?>

  <!-- Bootstrap, jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <?php
  if (isset($summernote_js)) {
    echo $summernote_js;
  }
  ?>
</head>

<body>

  <nav class="d-flex flex-column align-items-center justify-content-between">
    <div class="nav_aside_menu">
      <h1 class="top_logo d-flex justify-content-center">
        <a href="<?php echo isset($_SESSION['AUID']) ? '/qc/admin/index.php' : '/qc/admin/login.php'; ?>">
          <img src="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/core-img/Normal_Logo.svg" alt="탑 로고">
        </a>
      </h1>
      <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nav_cate_dashboard" aria-expanded="false" aria-controls="nav_cate_dashboard" id="dashboardButton">
              <img src="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/icon-img/SquaresFour.svg" alt="대시보드 아이콘"> 대시보드
            </button>
          </h2>
          <ul id="nav_cate_dashboard" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <li>대시보드</li>
          </ul>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nav_cate_Sales" aria-expanded="false" aria-controls="nav_cate_Sales">
              <img src="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/icon-img/ChartLineUp.svg" alt="매출관리 아이콘"> 매출관리
            </button>
          </h2>
          <ul id="nav_cate_Sales" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <li><a href="">매출목록</a></li>
          </ul>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nav_cate_lecture" aria-expanded="false" aria-controls="nav_cate_lecture">
              <img src="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/icon-img/Book.svg" alt="강의 관리 아이콘"> 강의 관리
            </button>
          </h2>
          <ul id="nav_cate_lecture" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/lecture/lecture_list.php">강의 목록</a></li>
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/lecture/lecture_insert.php">강의 등록</a></li>
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/lecture/category_list.php">카테고리 관리</a></li>
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/lecture/lecture_review.php">수강평</a></li>
          </ul>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nav_cate_member" aria-expanded="false" aria-controls="nav_cate_member">
              <img src="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/icon-img/UsersFour.svg" alt="회원 관리 아이콘"> 회원 관리
            </button>
          </h2>
          <ul id="nav_cate_member" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/members/member_list.php">회원 목록</a></li>
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/members/member_insert.php">회원 등록</a></li>
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/members/member_overview.php">회원 총괄</a></li>
          </ul>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nav_cate_instructor" aria-expanded="false" aria-controls="nav_cate_instructor">
              <img src="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/icon-img/ChalkboardSimple.svg" alt="강사 관리 아이콘"> 강사 관리
            </button>
          </h2>
          <ul id="nav_cate_instructor" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/teachers/teacher_list.php">강사 목록</a></li>
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/teachers/teacher_insert.php">강사 등록</a></li>
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/teachers/teacher_overview.php">강사 총괄</a></li>
          </ul>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nav_cate_coupon" aria-expanded="false" aria-controls="nav_cate_coupon">
              <img src="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/icon-img/Ticket.svg" alt="쿠폰 관리 아이콘"> 쿠폰 관리
            </button>
          </h2>
          <ul id="nav_cate_coupon" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/coupon/coupon_list.php">쿠폰 목록</a></li>
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/coupon/coupon_regis.php">쿠폰 등록</a></li>
          </ul>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#nav_cate_board" aria-expanded="false" aria-controls="nav_cate_board">
              <img src="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/img/icon-img/Article.svg" alt="게시판 관리 아이콘"> 게시판 관리
            </button>
          </h2>
          <ul id="nav_cate_board" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/t_board/t_board_list.php?category=notice">공지사항</a></li>
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/t_board/t_board_list.php?category=event">이벤트</a></li>
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/t_board/t_board_list.php?category=qna">Q&A</a></li>
            <li><a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/t_board/t_board_list.php?category=free">자유게시판</a></li>
          </ul>
        </div>
      </div>
    </div>

    <?php
    if (!isset($_SESSION['AUID'])) {
    ?>
      <div class="admin_account d-flex gap-3 align-items-center">
        <p class="tt_02">로그인 이전입니다.</p>
        <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/login.php">로그인</a>
      </div>

    <?php
    } else {
    ?>
      <div class="admin_account">
        <div class="d-flex gap-3 align-items-center mb-4">
          <img src="/qc/admin/img/core-img/어드민_이미지.png" alt="">
          <p class="tt_02"><?= $_SESSION['AUID'] ?></p>
        </div>
        <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/logout.php">로그아웃</a>
      </div>
    <?php
    }
    ?>
  </nav>

  <div class="nav_header">
    <h2 class="main_tt"> <?= $title ?></h2>
  </div>

  <div class="page_wapper">
    <div class="content">