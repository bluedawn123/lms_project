<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quantum Code</title>
    <!-- 제이쿼리랑 폰트어썸 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/css/common.css">
    <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST']; ?>/qc/css/core-style.css">

    <!-- 커스텀css... 필요하면 작성하나 비추 -->
    <style>
    .nav-item.dropdown:hover .dropdown-menu {
        display: block; /* 호버 시 드롭다운 메뉴 표시 */
        margin-top: 0; /* 자연스러운 위치 조정 */
    }
    .dropdown-toggle::after {
        display: none; /* 드롭다운 아이콘 숨김 */
    }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container">
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/index.php">
            <img src="./img/main_logo1.png" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown ms-3">
                    <a class="nav-link" href="#" id="lectureDropdown" role="button" aria-expanded="false">
                        강의
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="lectureDropdown">
                        <li><a class="dropdown-item" href="#">프론트엔드</a></li>
                        <li><a class="dropdown-item" href="#">백엔드</a></li>
                        <li><a class="dropdown-item" href="#">게임</a></li>   <!--커뮤니티 이벤트는 추후 작성-->
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">커뮤니티</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">이벤트</a>
                </li>
            </ul>
            <form class="d-flex search-form">
                <input class="form-control me-2" type="search" placeholder="검색" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">검색</button>
            </form>
            <div class="ms-3">
                <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/account/logintest2.php" class="btn btn-primary">로그인</a>
                <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/account/signup.php" class="btn btn-secondary">회원가입</a>
            </div>
        </div>
    </div>
</nav>


    <!-- Placeholder for Main Content -->
    <main class="container my-5">
         <!-- 메인 콘텐츠는 나중에 추가 -->
        <div class="text-center">
            <p>메인 콘텐츠는 여기에 추가됩니다.</p>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-md-start mb-3 mb-md-0">
                    <a href="#">공지사항</a>
                    <a href="#">이용약관</a>
                    <a href="#">FAQ</a>
                    <a href="#">개인정보 처리방침</a>
                    <a href="#">환불 규정</a>
                    <a href="#">고객센터</a>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="social-icons">
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                        <a href="#"><i class="bi bi-facebook"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <p>대표이사: 이코딩 | 개인정보보호관리자: 김코드<br>
            사업자번호: 000-00-00000 | 사업자정보확인<br>
            서울특별시 서대문구 가로수밑길 123, 코딩타워 10층 (우편번호: 12345)</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script> -->
     
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>
