<?php
$title = "게시판 수정";
$board_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/board.css\" rel=\"stylesheet\">";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/teachers/inc/header.php');

$id = isset($_SESSION['TUID']) ? $_SESSION['TUID'] : null;
if (!isset($id)) {
  echo "
    <script>
      alert('강사로 로그인해주세요');
      location.href = '../login.php';
    </script>
  ";
}

$category = $_GET['category'];
$pid = $_GET['pid'];

$sql = "SELECT * FROM board WHERE pid = $pid";
$result = $mysqli->query($sql);
$data = $result->fetch_object();
$start_date = date("Y-m-d", strtotime($data->start_date));
$end_date = date("Y-m-d", strtotime($data->end_date));


$cate_sql = "SELECT category FROM board WHERE pid=$pid";
$cate_result = $mysqli->query($cate_sql);
$cate_data = $cate_result->fetch_object();



$category = $cate_data->category ?? 'all';


switch ($category) {
  case 'all':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=" . $pid . "&category=all";
      break;
  case 'qna':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=" . $pid . "&category=qna"; 
      break;
  case 'notice':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=" . $pid . "&category=notice"; 
      break;
  case 'event':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=" . $pid . "&category=event"; 
      break;
  case 'free':
      $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=" . $pid . "&category=free"; 
      break;
  default:
      die("유효하지 않은 카테고리입니다.");
}


?>


<form action="board_modify_ok.php" method="POST" class="row" enctype="multipart/form-data">
  <input type="hidden" name="pid" value="<?=$data->pid?>">
  <input type="hidden" name="old_img" value="<?=$data->img?>">
  <div class="mb-3 col-4">
    <div class="box mb-3">
      <img id="imgPreview" src="<?=$data->img?>" alt="이미지 미리보기" style="display: <?= $data->is_img == 1 ? 'block' : 'none'; ?>; width: 100%; height: 100%; object-fit: contain;">
    </div>
    <input class="form-control" accept="image/*" name="file" type="file" id="file" onchange="previewImage(event)">
  </div>
  <div class="col-8">
    <select class="form-select w-25 mb-3" name="category" style="pointer-events: none; background-color: #ebebeb;" aria-label="Default select example">
      <option value="notice" <?= ($category == 'notice') ? 'selected' : ''; ?>>공지사항</option>
      <option value="free" <?= ($category == 'free') ? 'selected' : ''; ?>>자유게시판</option>
      <option value="event" <?= ($category == 'event') ? 'selected' : ''; ?>>이벤트</option>
      <option value="qna" <?= ($category == 'qna') ? 'selected' : ''; ?>>질문과답변</option>
    </select>
    <div class="mb-3 d-flex gap-3">
      <label for="title" class="form-label">제목:</label>
      <input type="text" class="form-control w-75" name="title" id="title" value="<?=$data->title?>" placeholder="제목입력">
    </div>
    <div class="mb-3 d-flex gap-3">
      <label for="content" class="form-label">내용:</label>
      <textarea class="form-control w-75" id="content" name="content" rows="3" value=""><?=$data->content?></textarea>
    </div>
    <!-- 카테고리값 이벤트 일시 폼 출력 -->
  <?php if ($data->category === 'event'): ?>
    <div class="form-group d-flex gap-3">
        <label for="start_date">시작일:</label>
        <input type="text" class="form-control datepicker w-75 mb-3" name="start_date" id="start_date" value="<?=$start_date?>">
    </div>
    <div class="form-group d-flex gap-3">
        <label for="end_date">종료일:</label>
        <input type="text" class="form-control datepicker w-75 mb-3" name="end_date" id="end_date" value="<?=$end_date?>">
    </div>
  <?php endif; ?>
  <!-- //카테고리값 이벤트 일시 폼 출력 -->
    <div class="d-flex justify-content-end gap-3">
      <button class="btn btn-primary">등록</button>
      <a href="<?=$redirect_url?>" class="btn btn-danger">취소</a>
    </div>
  </div>
</form>


<!-- datepicker 한글버전-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.kr.min.js"></script>

<script>
  document.getElementById('file').addEventListener('change', function(e) {
    const imgPreview = document.getElementById('imgPreview');
    const file = e.target.files[0];
    if (file) {
        imgPreview.src = URL.createObjectURL(file);
        imgPreview.style.display = 'block';
    }
});

  // Datepicker 활성화
  $('.datepicker').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true,
      language: 'kr' // 한글 로케일
  });
</script>


<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/footer.php');
?>