<?php
$title = '글 상세보기';
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

//관리자가 아닐시 로그인창으로 보내기
// if(!isset($_SESSION['AUID'])){
//   echo "
//     <script>
//       alert('관리자로 로그인해주세요');
//       location.href = '../login.php';
//     </script>
//   ";
// }


$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$pid = isset($_GET['pid']) ? $_GET['pid'] : null;


// 추천 쿼리
if (!isset($_SESSION['hits'])) {
  $_SESSION['hits'] = [];
}

if (!isset($_SESSION['hits'][$pid])) {
  $hit_sql = "UPDATE board SET hit = hit+1 WHERE pid=$pid";
  $hit_result = $mysqli->query($hit_sql);

  $_SESSION['hits'][$pid] = true;
};


if ($category === 'all') {
  $sql = "SELECT * FROM board WHERE pid = $pid";
} else {
  // 카테고리가 'all'이 아닌 경우, 카테고리 조건을 추가하여 쿼리 실행
  $sql = "SELECT * FROM board WHERE pid = $pid AND category = '$category'";
}
$result = $mysqli->query($sql);
$data = $result->fetch_object();

//이벤트 카테고리 날짜 값 변경문
$post_date = date("Y-m-d", strtotime($data->date));
$start_date = date("Y-m-d", strtotime($data->start_date));
$end_date = date("Y-m-d", strtotime($data->end_date));

switch ($category) {
  case 'all':
    $redirect_url = '/qc/admin/board/board_list.php?category=all';
    break;
  case 'qna':
    $redirect_url = '/qc/admin/board/board_list.php?category=qna';
    break;
  case 'notice':
    $redirect_url = '/qc/admin/board/board_list.php?category=notice';
    break;
  case 'event':
    $redirect_url = '/qc/admin/board/board_list.php?category=event';
    break;
  case 'free':
    $redirect_url = '/qc/admin/board/board_list.php?category=free';
    break;
  default:
    die("유효하지 않은 카테고리입니다.");
}


?>





<div class="d-flex justify-content-between">
  <h2>제목:<?= $data->title ?></h2>
  <span> <?= $data->category === 'event' ? '시작일: ' . ($data->start_date ? $start_date : '') . '~' . ' 종료일: ' . ($data->end_date ? $end_date : '') : '' ?> 글쓴이:<?= $data->user_id ?> <span id="like-count">추천수:<?= $data->likes ? $data->likes : 0 ?></span> 조회수:<?= $data->hit ? $data->hit : 0 ?> 등록일자:<?= $post_date ?></span>
</div>


<div class="mb-3">
  내용:<?= $data->content ?>
</div>
<div>
  <?php
  // 이미지가 있을시 출력
  if ($data->is_img == 1) {
    echo "<img src=\"{$data->img}\" width=\"300\" class=\"mb-3\">";
  }
  ?>
</div>
<div class="d-flex justify-content-end">
  <p class="d-flex gap-3">
    <?php if (((string)$category === 'qna'|| (string)$category === 'all') && (int)$data->status === 0): ?>
      <a href="board_answer_ok.php?pid=<?= $pid ?>&category=<?= $category ?>" class="btn btn-primary">답변완료</a>
    <?php endif ?>
    <?php if (((string)$category === 'qna'|| (string)$category === 'all') && (int)$data->status === 1): ?>
      <a href="board_answer_cancel.php?pid=<?= $pid ?>&category=<?= $category ?>" class="btn btn-danger">답변취소</a>
    <?php endif ?>
    <a href="<?= $redirect_url ?>" class="btn btn-secondary">목록</a>
    <a href="like_up.php?pid=<?= $pid ?>&category=<?= $category ?>" class="btn btn-info">추천</a>
    <a href="board_modify.php?pid=<?= $pid ?>&category=<?= $category ?>" class="btn btn-primary">수정</a>
    <a href="delete.php?pid=<?= $pid ?>&category=<?= $category ?>" class="btn btn-danger">삭제</a>
  </p>
</div>

<hr style="color:#0D6EFD;">
<!-- 댓글 -->
<form action="board_reply_ok.php" method="POST">
  <input type="hidden" name="pid" value="<?= $pid ?>">
  <input type="hidden" name="category" value="<?= $data->category ?>">
  <div class="d-flex gap-3 mb-3 align-items-center">
    <p>댓글 입력:</p>
    <textarea name="content" class="form-control w-25" placeholder="댓글내용을 입력 해주세요."></textarea>
    <button class="btn btn-primary btn-sm ">등록</button>
  </div>
</form>

<div class="" style="width: 18rem;">
  <ul class="list-group list-group-flush">
    <?php
    // 댓글 및 대댓글을 함께 가져오는 쿼리
    $reply_sql = "
      SELECT r.pid AS reply_id, r.user_id AS reply_user, r.date AS reply_date, r.content AS reply_content,
             rr.pid AS re_reply_id, rr.user_id AS re_reply_user, rr.date AS re_reply_date, rr.content AS re_reply_content, rr.r_pid
      FROM board_reply r
      LEFT JOIN board_re_reply rr ON r.pid = rr.r_pid
      WHERE r.b_pid = $pid
      ORDER BY r.date DESC, rr.date ASC
    ";
    $reply_result = $mysqli->query($reply_sql);

    // 댓글과 대댓글을 그룹화
    $replys = [];
    while ($re_data = $reply_result->fetch_object()) {
      if (!isset($replys[$re_data->reply_id])) {
        $replys[$re_data->reply_id] = [
          'user_id' => $re_data->reply_user,
          'date' => $re_data->reply_date,
          'content' => $re_data->reply_content,
          'pid' => $re_data->reply_id,
          'replies' => []
        ];
      }

      if ($re_data->re_reply_id) {
        $replys[$re_data->reply_id]['replies'][] = [
          'user_id' => $re_data->re_reply_user,
          'date' => $re_data->re_reply_date,
          'content' => $re_data->re_reply_content,
          'pid' => $re_data->re_reply_id,
          'r_pid' => $re_data->r_pid
        ];
      }
    }

    // 댓글과 대댓글 출력
    foreach ($replys as $replay_id => $reply) {
    ?>
      <!-- 댓글 출력 -->
      <li class="list-group-item mb-3" style="border: 1px solid blue; border-radius:15px">
        <div class="contents">
          <div class="d-flex justify-content-between">
            <small><?= $reply['user_id'] ?></small>
            <small><?= $reply['date'] ?></small>
          </div>
          <hr>
          <div class="content mb-3">
            <?= $reply['content'] ?>
          </div>
          <div class="controls d-flex justify-content-end gap-1">
            <button class="btn btn-secondary sm" onclick="toggleReplyForm(<?= $replay_id ?>)">대댓글</button>
            <button class="btn btn-primary sm" data-bs-toggle="modal" data-bs-target="#reply_edit<?= $replay_id ?>">수정</button>
            <a href="reply_delete.php?pid=<?= $replay_id ?>&b_pid=<?= $pid ?>&category=<?= $category ?>" class="btn btn-danger sm">삭제</a>
          </div>
          <!-- modal -->
          <div class="modal fade" id="reply_edit<?= $replay_id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <form action="board_reply_modify_ok.php" method="POST" class="modal-content">
                <input type="hidden" name="pid" value="<?= $reply['pid'] ?>">
                <input type="hidden" name="b_pid" value="<?= $pid ?>">
                <input type="hidden" name="category" value="<?= $category ?>">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">댓글 수정</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <?= $reply['user_id'] ?>
                  <hr>
                  <textarea name="content" class="form-control mt-3"> <?= $reply['content'] ?></textarea>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">확인</button>
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">취소</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </li>

      <!-- 대댓글 입력폼-->
      <form action="board_re_reply_ok.php" method="POST" id="replyForm<?= $replay_id ?>" style="display:none;">
        <input type="hidden" name="r_pid" value="<?= $replay_id ?>">
        <input type="hidden" name="pid" value="<?= $pid ?>">
        <input type="hidden" name="category" value="<?= $data->category ?>">
        <div class="d-flex gap-3 mb-3 align-items-center">
          <p>댓글 입력:</p>
          <input name="content" class="form-control w-50" placeholder="내용입력."></textarea>
          <button class="btn btn-primary btn-sm ">등록</button>
        </div>
      </form>

      <!-- 대댓글 출력 -->
      <?php if (!empty($reply['replies'])): ?>
        <ul class="list-group list-group-flush ms-4 mt-2"><i class="fa-regular fa-hand-point-right"></i>

          <?php foreach ($reply['replies'] as $re_reply): ?>
            <li class="list-group-item mb-3 w-100" style="border: 1px solid red; border-radius:15px">
              <div class="contents">
                <div class="d-flex justify-content-between">
                  <small><?= $re_reply['user_id'] ?></small>
                  <small><?= $re_reply['date'] ?></small>
                </div>
                <hr>
                <div class="mb-3">
                  <?= $re_reply['content'] ?>
                </div>
                <div class="controls d-flex justify-content-end gap-1">
                  <button type="button" class="btn btn-primary sm" data-bs-toggle="modal" data-bs-target="#re_reply_edit<?= $re_reply['pid'] ?>">수정</button>
                  <a href="re_reply_delete.php?re_reply_pid=<?= $re_reply['pid'] ?>&pid=<?= $pid ?>&reply_id=<?= $reply['pid'] ?>&category=<?= $category ?>" class="btn btn-danger btn-sm">삭제</a>
                </div>
              </div>
              <!-- 대댓글 수정 Modal -->
              <div class="modal fade" id="re_reply_edit<?= $re_reply['pid'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <form action="re_reply_modify_ok.php" method="POST" class="modal-content">
                    <input type="hidden" name="category" value="<?= $category ?>">
                    <input type="hidden" name="b_pid" value="<?= $reply['pid'] ?>">
                    <input type="hidden" name="pid" value="<?= $re_reply['pid'] ?>">
                    <input type="hidden" name="r_pid" value="<?= $re_reply['r_pid'] ?>">
                    <input type="hidden" name="list_pid" value="<?= $pid ?>">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">대댓글 수정</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <?= $re_reply['user_id'] ?>
                      <hr />
                      <textarea name="content" class="form-control mt-3"> <?= $re_reply['content'] ?></textarea>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">확인</button>
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal">취소</button>
                    </div>
                  </form>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

    <?php
    }
    ?>
  </ul>
</div>


<script>
  function toggleReplyForm(replyId) {
    let form = document.getElementById('replyForm' + replyId);
    // 폼이 보이면 숨기고, 숨겨져 있으면 보이게 설정
    if (form.style.display === 'none') {
      form.style.display = 'block';
    } else {
      form.style.display = 'none';
    }
  }
</script>


<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>