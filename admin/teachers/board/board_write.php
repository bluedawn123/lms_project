<?php
$title = '게시판 글등록';
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
?>


<form action="board_write_ok.php" method="POST" enctype="multipart/form-data" class="row">
  <div class="mb-3 col-4">
    <div class="box mb-3">
      <img id="imgPreview" src="#" alt="이미지 미리보기" style="display:none; width: 100%; height: 100%; object-fit: contain;">
    </div>
    <input class="form-control" accept="image/*" name="file" type="file" id="file" onchange="previewImage(event)">
  </div>
  <div class="col-8">
    <select id="category" class="form-select w-25 mb-3" name="category" aria-label="Default select example" required >
      <option value="" selected>카테고리 선택</option>
      <option value="notice">공지사항</option>
      <option value="free">자유게시판</option>
      <option value="qna">질문과답변</option>
    </select>
    <div class="mb-3 d-flex gap-3">
      <label for="title" class="form-label">제목:</label>
      <input type="text" class="form-control w-75" name="title" id="title" placeholder="제목입력" required>
    </div>
    <div class="mb-3 d-flex gap-3">
      <label for="content" class="form-label">내용:</label>
      <textarea class="form-control w-75" id="content" name="content" rows="3" value="" required></textarea>
    </div>
    <!-- 이벤트 게시판에서만 날짜 입력 -->
    <div id="event-dates" style="display: none;">
      <div class="form-group d-flex gap-3">
        <label for="start_date">시작일:</label>
        <input type="text" class="form-control datepicker w-75 mb-3" name="start_date" id="start_date">
      </div>
      <div class="form-group d-flex gap-3">
        <label for="end_date">종료일:</label>
        <input type="text" class="form-control datepicker w-75 mb-3" name="end_date" id="end_date">
      </div>
    </div>
    <div class="d-flex justify-content-end gap-3" style="margin-right:155px;">
      <button type="submit" class="btn btn-primary">등록</button>
      <button id="cancel" class="btn btn-danger">취소</button>
    </div>
  </div>
</form>

<!-- datepicker 한글버전-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.kr.min.js"></script>

<script>
  // 취소 버튼 클릭 시 게시물 페이지로 돌아가기
  document.getElementById('cancel').addEventListener('click', function() {
    window.location.href = 't_board_list.php'; 
  });


  //이미지 미리보기
  function previewImage(event){
    const file = event.target.files[0]; //선택한 파일을 가져옴
    const reader = new FileReader(); // 파일리더 객체 생성

    reader.onload = function(e){
      const imgPreview = document.querySelector('#imgPreview');
      imgPreview.src = e.target.result; //미리보기 이미지 설정
      imgPreview.style.display="block";
    }

    if(file) {
      reader.readAsDataURL(file); //파일을 data url 형식으로 읽음
    }

  }


  $(document).ready(function() {
        // 카테고리 변경 시 시작일/종료일 필드 보이기/숨기기
        $('#category').change(function() {
            var category = $(this).val();
            if (category === 'event') {
                $('#event-dates').show();  // 이벤트 카테고리일 때만 날짜 입력 필드 보이기
            } else {
                $('#event-dates').hide();  // 그 외 카테고리일 때 날짜 입력 필드 숨기기
            }
        }).trigger('change'); // 페이지 로드 시 자동으로 카테고리 값에 따라 동작

        // Datepicker 활성화
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: 'kr' // 한글 로케일
        });

    });

</script>
 
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/teachers/inc/footer.php');
?>