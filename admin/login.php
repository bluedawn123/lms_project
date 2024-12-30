<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Title -->
  <title>관리자 로그인 - quantumcode</title>

  <!-- Favicon -->

  <!-- Core Style CSS -->
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/css/core-style.css">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/admin/css/login.css">

  <!-- Bootstrap, jQuery -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>

<body>

  <div class="insite_modal">
    <div class="modal_header d-flex flex-column justify-content-center align-items-center">
      <img src="img/core-img/Small_Logo.svg" class="mb-2" alt="포트폴리오용 모달 로고">
      <p class="mb-4">LMS 관리자 페이지 제작 프로젝트1</p>
      <h2>본 사이트는 구직용 포트폴리오 웹사이트이며, 실제로 운영되는 사이트가 아닙니다.</h2>
    </div>
    <hr>
    <div class="modal_info">
      <p>팀명 : <span>CODE TITANIC</span> 나*일(팀장), 윤*호, 남*우, 김*진</p>
      <p>제작기간 : 2024.10.10 ~ 2024.11.24</p>
      <div>
        <p class="info_link d-flex align-items-center gap-2">
          기획서 :
          <a href="https://www.figma.com/design/cvKHOIykNi0skaglbud6Ry/%ED%80%80%ED%85%80-%EC%BD%94%EB%93%9C?node-id=35-4&t=rUO73zwMONWqOw25-1" target="_blank"> 피그마</a>
          <img src="img/icon-img/github-mark.svg" alt="피그마 아이콘">
          코드 : <a href="https://github.com/Naseungil/quantumcode" target="_blank">
            깃허브</a>
          <img src="img/icon-img/icons_figma.svg" alt="깃허브 아이콘" class="github">
        </p>
      </div>
      <p>개발환경 : html5, css3, javascript, php, mySQL</p>
    </div>
    <hr>
    <div class="modal_work">
      <h4>업무분담</h4>
      <p class="mb-3">기획 및 디자인 : 팀원 전체</p>
      <h4>- 구현 완료 페이지 -</h4>
      <p>나승일 : 게시판 관리</p>
      <p>윤준호 : 회원, 강사, 강사개인페이지, 로그인(기능)</p>
      <p>남성우 : 강의, 매출</p>
      <p>김유진 : 로그인(디자인), 헤더, 쿠폰(등록·수정·삭제)</p>
    </div>
    <hr>
    <div class="modal_control d-flex justify-content-between align-items-center">
      <form>
        <label for="modal_check">하루 동안 보지 않기 </label>
        <input type="checkbox" id="modal_check">
      </form>
      <button class="btn">닫기</button>
    </div>
  </div>

  <div class="d-flex">
    <aside>
      <div class="copy">
        <h2>Connect your Dream with Our passion</h2>
        <h3>everything you can imagine is can be possible with us</h3>
      </div>
    </aside>

    <div class="login_container d-flex flex-column justify-content-center align-items-center">
      <h1 class="main_tt">Admin Account</h1>
      <div class="row login_box">
        <form action="login_ok.php" method="POST">
          <div class="form-floating">
            <input type="text" class="form-control" id="userid" name="userid" placeholder="Admin">
            <label for="userid">Admin</label>
          </div>
          <div class="form-floating">
            <input type="password" class="form-control" id="userpw" name="userpw" placeholder="1111">
            <label for="userpw">1111</label>
          </div>
          <div class="d-flex justify-content-between">
            <p class="mt-4 mb-4"><a href="#" class="forgotpw">Forgot Password?</a></p>
            <p class="mt-4 mb-4"><a href="login_teacher.php" class="login_change">Log in to Teacher</a></p>
          </div>
          <button class="btn btn-primary">Log in</button>
        </form>
      </div>
    </div>

  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const insite_modal = document.querySelector('.insite_modal');
      const check = document.querySelector('#modal_check');
      const button = document.querySelector('.insite_modal button');

      button.addEventListener('click', () => {
        if (check.checked) {
          setCookie('insite', 'today', 1);
        } else {
          delCookie('insite', 'today');
        }
        insite_modal.classList.remove('show');
      });

      function setCookie(name, val, due) {
        let date = new Date();
        date.setDate(date.getDate() + due);

        let myCookie = `${name}=${val};expires=` + date.toUTCString();
        document.cookie = myCookie;
      }

      function delCookie(name, val) {
        let date = new Date();
        date.setDate(date.getDate() - 1);

        let myCookie = `${name}=${val};expires=` + date.toUTCString();
        document.cookie = myCookie;
      }

      function checkCookie(name, val) {
        if (document.cookie.search(`${name}=${val}`) === -1) {
          insite_modal.classList.add('show');
        }
      }

      checkCookie('insite', 'today');
    });
  </script>

  <?php
  include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
  ?>