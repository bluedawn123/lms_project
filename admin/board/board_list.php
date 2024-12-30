<?php
$title = '게시판';
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/header.php');

//관리자가 아닐시 로그인창으로 보내기
// if(!isset($_SESSION['AUID'])){
//   echo "
//     <script>
//       alert('관리자로 로그인해주세요');
//       location.href = '../login.php';
//     </script>
//   ";
// }

//검색창 검색어 받기
$search_keyword = $_POST['search_keyword'] ?? '';

// 검색 조건
$search_where = '';
if ($search_keyword) {
    $search_where = " AND board.title LIKE '%$search_keyword%'";
}

// 카테고리 값 받기
$category = $_GET['category'] ?? 'all';

$category = isset($_GET['category']) ? $_GET['category'] : 'all';


// 게시글 수 쿼리
$count_sql = "SELECT COUNT(*) as total FROM board WHERE 1=1";

if ($category !== 'all') {
    $count_sql .= " AND category = '$category'";
}
if ($search_keyword) {
    $count_sql .= " AND content LIKE '%$search_keyword%'";
}

// 실행 및 결과 가져오기
$count_result = $mysqli->query($count_sql);
$total_count = $count_result->fetch_object()->total;


// 페이지 번호 계산
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 목록 개수와 시작 번호 설정
$list = 10; // 한 페이지에 표시할 게시물 개수
$start_num = ($page - 1) * $list;

// 전체 페이지 계산
$total_page = ceil($total_count / $list);

// 페이지네이션을 위한 블록 크기 (각 블록에 포함할 페이지 개수)
$block_ct = 5;  // 블록당 페이지 수
$total_block = ceil($total_page / $block_ct);  // 총 블록 수

// 현재 페이지가 속한 블록 계산
$block_num = ceil($page / $block_ct);

// 현재 블록의 시작 페이지와 끝 페이지 계산
$block_start = (($block_num - 1) * $block_ct) + 1;
$block_end = $block_start + $block_ct - 1;

// 블록 끝 페이지가 총 페이지 수를 초과하면, 마지막 페이지를 끝으로 설정
if ($block_end > $total_page) {
    $block_end = $total_page;
}

// 이전, 다음 페이지 계산
$prev = max(1, $block_start - 1);
$next = min($total_page, $block_end + 1);



// 종료일이 지난 게시물을 자동으로 삭제
$event_delete_sql = "DELETE FROM board WHERE category = 'event' AND end_date < NOW()";
$mysqli->query($event_delete_sql);

// 이벤트 게시물 목록을 조회
$event_sql = "SELECT * FROM board WHERE category = 'event'";  // 이벤트 카테고리만 조회
$event_result = $mysqli->query($event_sql);







// SQL 쿼리 작성

if ($category == 'all') {
  $sql = "SELECT * FROM board WHERE 1=1 $search_where ORDER BY pid DESC LIMIT $start_num, $list";
} else {
  $sql = "SELECT * FROM board WHERE category = '$category' $search_where ORDER BY pid DESC LIMIT $start_num, $list";
}

$result = $mysqli->query($sql);


?>

<div class="container">
  <form action="board_list.php?category=<?=$category?>" method="POST" class="board_serch d-flex align-items-center justify-content-between mb-3">
    <select id="categorySelect" class="form-select w-25" name="category">
      <option value="all">전체 게시판</option>
      <option value="notice" <?= $category == 'notice' ? 'selected' : '' ?>>공지사항</option>
      <option value="free" <?= $category == 'free' ? 'selected' : '' ?>>자유게시판</option>
      <option value="event" <?= $category == 'event' ? 'selected' : '' ?>>이벤트</option>
      <option value="qna" <?= $category == 'qna' ? 'selected' : '' ?>>질문과답변</option>
    </select>
    <div class="d-flex w-100 justify-content-end gap-3">
      <input type="text" class="form-control w-25" name="search_keyword" placeholder="게시물 제목을 입력 해주세요" value="<?=$search_keyword?>">
      <button type="submit" class="btn btn-primary">검색</button>
    </div>
  </form>


  <table class="table table-hover mb-3">
    <thead>
      <tr>
        <th scope="col"><i class="fa-solid fa-check"></i></th>
        <th scope="col">No</th>
        <th scope="col">제목</th>
        <th scope="col">글쓴이</th>
        <th scope="col">내용</th>
        <?php if($category == 'qna'):?>
        <th scope="col">답변상태</th>
        <?php endif?>
        <th scope="col">카테고리</th>
        <th scope="col">등록일</th>
        <th scope="col">추천수</th>
        <th scope="col">조회수</th>
        <th scope="col">Edit</th>
      </tr>
    </thead>
    <tbody id="board_list">
      <?php
      // 게시글 출력
      while($data = $result->fetch_object()){
        $post_date = date("Y-m-d", strtotime($data->date)); // date 컬럼의 타임스탬프를 Y-m-d 형식으로 변환
        $current_date = date("Y-m-d");

        if($post_date == $current_date){
          $icon = "<i class=\"fa-solid fa-dove\" style=\"color: red;\"></i>";
        }else{
          $icon = '';
        }

        if($data->status == 1){
          $answer_icon = "<i class=\"fa-regular fa-paper-plane\"></i> 완료";
        }else{
          $answer_icon = '미완료';
        }
        $content = $data->content;
        $title1 = $data->title;
        // 제목이 길 경우 10글자로 자르기
        if(iconv_strlen($title1) > 10){
          $title1 = iconv_substr($title1, 0, 10) . '...';
        }
        if(iconv_strlen($content) > 10){
          $content = iconv_substr($content, 0, 10) . '...';
        }
        ?>
      <tr>
        <th><input type="checkbox" id="selectAll" class="delete_checkbox form-check-input" value="<?= $data->pid ?>"></th>
        <th scope="row"><?= $data->pid ?></th>
        <td><a href="read.php?pid=<?=$data->pid?>&category=<?=$category?>"><?=$title1?> <?=$icon?></a></td>
        <td><?=$data->user_id?></td>
        <td><?=$content ?></td>
        <?php if($category == 'qna'):?>
        <td><?=$answer_icon ?></td>
        <?php endif?>
        <td><?= $data->category === 'notice' ? '공지사항' : ($data->category === 'event' ? '이벤트' : ($data->category === 'qna' ? '질문과답변' : ($data->category === 'free' ? '자유게시판' : ($data->category)))) ?></td>
        <td><?=$post_date ?></td>
        <td><?=$data->likes ? $data->likes : 0 ?></td>
        <td><?=$data->hit ? $data->hit : 0 ?></td>
        <td>
          <a href="board_modify.php?pid=<?=$data->pid?>&category=<?=$category?>"><i class="fa-regular fa-pen-to-square" style="color:black;"></i></a>
          <a href="delete.php?pid=<?=$data->pid?>&category=<?=$category?>"><i class="fa-regular fa-trash-can" style="color:black;"></i></a>
        </td>
      </tr>
      <?php
      }
      ?>
    </tbody>
  </table>

  <nav aria-label="Page navigation">
    <ul class="pagination">
      <?php
        if ($block_num > 1) { //prev 버튼
          $prev = $block_start - $block_ct;
          echo "<li class=\"page-item prev\">
              <a class=\"page-link\" href=\"board_list.php?category={$category}&page={$prev}\">
                  <img src=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/img/icon-img/CaretLeft.svg\" alt=\"페이지네이션 prev\">
              </a>
          </li>";
        }
      ?>
        
      <?php
        // 페이지 번호 표시
        for ($i = $block_start; $i <= $block_end; $i++) {                
          $active = ($page == $i) ? 'active' : '';
      ?>
      <li class="page-item <?= $active; ?>"><a class="page-link" href="board_list.php?category=<?=$category?>&page=<?= $i; ?>&search_keyword=<?=$search_keyword?>"><?= $i; ?></a></li>
      <?php
        }
        $next = $block_end + 1;
        if($total_block >  $block_num){ //next 버튼
      ?>
      <li class="page-item next">
        <a class="page-link" href="board_list.php?category=<?=$category?>&page=<?= $next;?>">
          <img src="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/img/icon-img/CaretRight.svg" alt="페이지네이션 next">
        </a>
      </li>
      <?php
      }         
      ?>
    </ul>
  </nav>

  <div class=" d-flex justify-content-end gap-3">
    <a class="btn btn-primary" href="board_write.php" role="button">글등록</a>
    <button type="button" id="deleteSelected" class="btn btn-danger" href="#" role="button">글삭제</button>
  </div>
</div>

<!-- <script>
  // 카테고리 선택 시
  const cate = document.querySelector('#categorySelect');
  cate.addEventListener('change', function(e) {
    e.preventDefault();
    const category = this.value;

    // AJAX 요청으로 데이터만 갱신
    $.ajax({
      url: 'board_list.php',  // 요청할 PHP 파일
      type: 'GET',
      data: { category: category },  // 보낼 데이터 (카테고리 값)
      success: function(data) {
        $('#board_list').html($(data).find('#board_list').html());
      },
      error: function(xhr, status, error) {
        console.error('AJAX Error:', error);
      }
    });
  });
</script> -->

<script>
  // 카테고리 선택 시
  const cate = document.querySelector('#categorySelect');
  cate.addEventListener('change', function() {
    const category = this.value;
  location.href=`?category=${category}`;
  });



  //체크박스 선택 시
  const deleteSelected = document.querySelector('#deleteSelected');
  
  deleteSelected.addEventListener('click',()=>{
    const selectedIds = Array.from(document.querySelectorAll('.delete_checkbox:checked')).map(checkbox => checkbox.value);

   // console.log(selectedIds);

    if(selectedIds.length === 0){
    alert('삭제할 게시물을 선택해주세요.');
    return; // fetch 요청이 실행되지 않도록 함 alert 창 중복방지
  }
  const requestData = JSON.stringify(selectedIds);

  fetch('check_delete.php',{
    method: 'POST',
    headers: {
      'Content-Type' : 'application/json',
    },
    body:requestData
  })
  .then(response => response.text())
  .then(data => {
    const userConfirmed = confirm("게시물을 삭제하겠습니까?");
  
    if (userConfirmed) {
      location.reload(); // 사용자가 '확인'을 클릭하면 페이지 새로 고침
    }
  })
  .catch(error => console.error('Error:', error));
  });



</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/footer.php');
?>