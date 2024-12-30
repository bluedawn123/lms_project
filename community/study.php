<?php 
$title = "스터디 모집";
$community_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/community.css\" rel=\"stylesheet\">";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');
// print_r($_SESSION); 잘나옴;

// 페이지네이션 설정
$items_per_page = 10; // 한 페이지에 표시할 항목 수
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // 현재 페이지

if ($current_page < 1) $current_page = 1; // 페이지 범위 제한

// 총 게시물 수 계산
$total_items_sql = "SELECT COUNT(*) AS total FROM board WHERE category = 'study'";
$total_items_result = $mysqli->query($total_items_sql);
$total_items_row = $total_items_result->fetch_assoc();
$total_items = $total_items_row['total'];

// 총 페이지 수 계산
$total_pages = ceil($total_items / $items_per_page);
if ($current_page > $total_pages) $current_page = $total_pages;

// 데이터 가져오기
$start_index = ($current_page - 1) * $items_per_page;
$sql = "SELECT pid, title, content, user_id, hit, likes, recruit_status, date FROM board WHERE category = 'study' ORDER BY date DESC LIMIT ?, ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $start_index, $items_per_page);
$stmt->execute();
$result = $stmt->get_result();
$qnas = $result->fetch_all(MYSQLI_ASSOC);  //qnas는 그냥 써둔거... 위으 sql이 기준점입니다.


?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
  .modal-dialog {
    max-width: 700px; /* 원하는 너비로 변경 */
    width: 80%; /* 반응형으로 설정 */
  }

  /* 모달 외곽 스타일 */
  .modal-content {
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    border: none;
    overflow: hidden;
    min-height: 400px; /* 최소 높이 설정 */
    max-height: 80vh; /* 최대 높이 설정 */
  }

  /* 헤더 스타일 */
  .modal-header {
    background-color: #007bff;
    color: #fff;
    padding: 20px;
    border-bottom: none;
  }

  .modal-title {
    font-size: 1.8rem;
    font-weight: bold;
  }

  .btn-close {
    background: rgba(255, 255, 255, 0.7);
    border-radius: 50%;
    opacity: 1;
  }

  /* 본문 스타일 */
  .modal-body {
    padding: 20px;
    background-color: #f9f9f9;
  }

  #modalContent {
    font-size: 1.1rem;
    color: #555;
    line-height: 1.6;
  }

  /* 댓글 스타일 */
  #commentSection h6 {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 15px;
    color: #333;
  }

  .list-group-item {
    margin-bottom: 10px; /* 댓글 사이 간격 추가 */
    border-radius: 8px;
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 10px 15px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

  #commentInput {
    border-radius: 10px;
    border: 1px solid #ddd;
    padding: 10px;
    font-size: 1rem;
    resize: none;
    transition: all 0.3s ease;
  }

  #commentInput:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
  }

  .btn-primary {
    background-color: #007bff;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 1rem;
    font-weight: bold;
    color: #fff;
    transition: background-color 0.3s ease;
  }

  .btn-primary:hover {
    background-color: #0056b3;
  }

  /* 푸터 스타일 */
  .modal-footer {
    padding: 15px;
    background-color: #f1f1f1;
    border-top: none;
    text-align: right;
  }

  /* --- */
   
  #inquiryButton {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 8px 16px;
    font-size: 14px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  #inquiryButton:hover {
    background-color: #0056b3;
  }

  /* 댓글 버튼 */
  .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
  }

  .btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
  }
</style>

<div class="search_box">
  <div class="container">
    <h2><?= $title ?></h2>
    <form action="" class="d-flex">
      <input type="text" class="form-control" id="search_box" placeholder="검색어를 입력하세요.">
      <button type="submit" class="btn"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
  </div>  
</div>

<div class="community container">
  <div class="row">
    <aside class="col-2 d-flex flex-column">
      <h6>커뮤니티</h6>
      <hr>
      <ul>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/notice.php"><li>공지사항<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/faq.php"><li>FAQ<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/qna.php"><li>QnA<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/board.php"><li>자유게시판<i class="fa-solid fa-chevron-right"></i></li></a>
        <a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/community/study.php" class="active"><li>스터디 모집<i class="fa-solid fa-chevron-right"></i></li></a>
      </ul>
    </aside>
  
    <div class="qna content col-10">
      <div class="d-flex justify-content-between align-items-center">
        <h6>함께 공부할 수강생을 직접 찾아보세요!</h6>
        <!-- 문의하기 버튼 -->
        <?php
          if(isset($_SESSION['MemEmail']))

          {
          ?>
        <button class="btn btn-primary" id="inquiryButton">동료 찾기</button>
        <?php
        }
        ?>
      </div>
      <hr>
      <table class="table table-hover text-center">
        <thead>
          <tr>
            <th scope="col" class="num" style="width: 5%;">No</th>
            <th scope="col" style="width: 20%;">제목</th>
            <th scope="col" style="width: 30%;">내용</th>
            <th scope="col" style="width: 10%;">글쓴이</th>
            <th scope="col" style="width: 7.5%;">조회</th>
            <th scope="col" style="width: 7.5%;">리플</th>
            <th scope="col" style="width: 13%;">모집현황</th>
            <th scope="col" style="width: 20%;">게시일</th>
          </tr>
        </thead>
        <tbody>
  <?php foreach ($qnas as $index => $qna): ?>
    <tr>
      <th scope="row"><?= $total_items - $start_index - $index ?></th>
      <td class="post">
        <a href="#" 
          data-bs-toggle="modal" 
          data-bs-target="#contentModal" 
          data-id="<?= $qna['pid'] ?>" 
          data-title="<?= htmlspecialchars($qna['title']) ?>" 
          data-content="<?= htmlspecialchars($qna['content']) ?>">
          <?= htmlspecialchars(mb_substr($qna['title'], 0, 11) . (mb_strlen($qna['title']) > 11 ? "..." : "")) ?>
        </a>
      </td>
      <td><?= htmlspecialchars(mb_substr($qna['content'], 0, 18) . (mb_strlen($qna['content']) > 18 ? "..." : "")) ?></td>
      <td><?= htmlspecialchars($qna['user_id']) ?></td>
      <td><?= htmlspecialchars($qna['hit']) ?></td>
      <td><?= htmlspecialchars($qna['likes']) ?></td>
      <td>
        <?php
          // recruit_status 값을 조건에 따라 출력
          if (isset($qna['recruit_status'])) {
            echo $qna['recruit_status'] == 0 ? "모집중" : "모집완료";
          } else {
            echo "-"; // recruit_status 값이 없는 경우
          }
        ?>
        <!-- 로그인한 사용자와 작성자가 동일하면 마감하기 버튼 표시 -->
        <?php if ($qna['user_id'] === $email && $qna['recruit_status'] == 0): ?>
          <form method="post" action="/qc/community/close_recruit.php" style="display:inline;">
            <input type="hidden" name="pid" value="<?= $qna['pid'] ?>">
            <?php if ($qna['user_id'] === $email && $qna['recruit_status'] == 0): ?>
              <button 
                class="btn btn-sm btn-danger close-recruit-btn" 
                data-id="<?= $qna['pid'] ?>">마감하기</button>
            <?php endif; ?>
          </form>
        <?php endif; ?>
      </td>
      <td><?= htmlspecialchars($qna['date']) ?></td>
    </tr>
  <?php endforeach; ?>
</tbody>
      </table>
      
      <nav aria-label="Page navigation">
        <ul class="pagination">
          <li class="page-item"><a class="page-link" href="#"><img src="../img/icon-img/CaretLeft.svg" alt=""></a></li>
          <li class="page-item"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#"><img src="../img/icon-img/CaretRight.svg" alt=""></a></li>
        </ul>
      </nav>
    </div>
  </div>
</div>

<!-- 스터디 모집 글쓰기 모달 -->
<div class="modal fade" id="inquiryModal" tabindex="-1" aria-labelledby="inquiryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="inquiryModalLabel">스터디 모집 글쓰기</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="inquiryForm" method="post">
          <div class="mb-3">
            <label for="inquiryTitle" class="form-label">글 제목</label>
            <input type="text" class="form-control" id="inquiryTitle" name="title" placeholder="제목을 입력하세요" required>
          </div>
          <div class="mb-3">
            <label for="inquiryContent" class="form-label">글 내용</label>
            <textarea class="form-control" id="inquiryContent" name="content" rows="10" placeholder="내용을 입력하세요" required></textarea>
          </div>
          <div class="mb-3 text-end">
            <button type="submit" class="btn btn-primary">제출하기</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- 게시물 상세 내용 모달 -->
<div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="contentModalLabel">게시물 내용</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5 id="modalTitle">제목</h5>
        <p id="modalContent">내용</p>

        <hr>
        <!-- 댓글 영역 -->
        <div id="commentSection">
          <h6>댓글</h6>
          <ul id="commentList" class="list-group mb-3">
            <!-- 댓글 목록이 동적으로 추가됩니다 -->
          </ul>
          <!-- 댓글 입력 -->
          <textarea id="commentInput" class="form-control mb-2" rows="2" placeholder="댓글을 입력하세요..."></textarea>
          <button id="addCommentButton" class="btn btn-primary">댓글 추가</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // 게시물 상세 보기 모달 표시
  const contentModal = document.getElementById("contentModal");
  contentModal.addEventListener("show.bs.modal", function (event) {
    const button = event.relatedTarget; // 클릭된 버튼 요소
    const postId = button.getAttribute("data-id"); // 게시물 ID
    const title = button.getAttribute("data-title");
    const content = button.getAttribute("data-content");

    // 모달 내부 요소 업데이트
    const modalTitle = document.getElementById("modalTitle");
    const modalContent = document.getElementById("modalContent");

    modalTitle.textContent = title;
    modalContent.textContent = content;

    // 조회수 증가 요청
    $.ajax({
      url: "/qc/community/increase_hit.php",
      type: "POST",
      data: { id: postId },
      success: function (response) {
        if (response.status === "success") {
          console.log("조회수 증가 성공:", response.message);
        } else {
          console.error("조회수 증가 실패:", response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("조회수 증가 요청 에러:", error);
      },
    });

    // 댓글 목록 불러오기
    fetchComments(postId);

    // 댓글 추가 버튼 이벤트 등록
    document.getElementById("addCommentButton").onclick = function () {
      addComment(postId);
    };
  });

  // 댓글 목록 불러오기
  function fetchComments(postId) {
    $.ajax({
      url: "/qc/community/get_comments.php", // 댓글 가져오기 PHP 파일
      type: "GET",
      data: { post_id: postId },
      success: function (response) {
        if (response.status === "success") {
          const commentList = document.getElementById("commentList");
          commentList.innerHTML = ""; // 기존 댓글 초기화
          response.comments.forEach(comment => {
            const listItem = document.createElement("li");
            listItem.className = "list-group-item";
            listItem.textContent = `${comment.content} (작성자: ${comment.user_id}, 작성일: ${comment.created_at})`;
            commentList.appendChild(listItem);
          });
        } else {
          alert(response.message);
        }
      },
      error: function () {
        alert("댓글 불러오기 중 오류가 발생했습니다.");
      },
    });
  }

  // 댓글 추가
  function addComment(postId) {
    const commentInput = document.getElementById("commentInput");
    const commentText = commentInput.value.trim();

    if (!commentText) {
      alert("댓글 내용을 입력해주세요.");
      return;
    }

    $.ajax({
      url: "/qc/community/reply_ok.php", // 댓글 추가 PHP 파일
      type: "POST",
      data: {
        post_id: postId,
        content: commentText,
      },
      success: function (response) {
        if (response.status === "success") {
          fetchComments(postId); // 댓글 목록 갱신
          commentInput.value = ""; // 입력 필드 초기화
          alert("댓글이 입력되었습니다."); // 성공 메시지
        } else {
          alert(response.message);
        }
      },
      error: function () {
        alert("댓글 추가 중 오류가 발생했습니다.");
      },
    });
  }

  // 글쓰기 모달 표시
  $("#inquiryButton").on("click", function () {
    const inquiryModal = new bootstrap.Modal(document.getElementById("inquiryModal"));
    inquiryModal.show();
  });

  // 글쓰기 폼 제출 처리
  $("#inquiryForm").on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    $.ajax({
      url: "/qc/community/study_ok.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.status === "success") {
          alert(response.message);
          location.reload();
        } else {
          alert(response.message);
        }
      },
      error: function () {
        alert("글 작성 중 오류가 발생했습니다. 다시 시도해주세요.");
      },
    });
  });

  
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/footer.php');
?>