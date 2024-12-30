<?php
$title = '게시판';
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


$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// 카테고리별 게시물 개수 조회 (전체 게시판이 아닌 카테고리별로)
if ($category == 'all') {
  $page_sql = "SELECT COUNT(*) AS count FROM board";
} else {
  $page_sql = "SELECT COUNT(*) AS count FROM board WHERE category = '$category'";
}
$page_result = $mysqli->query($page_sql);
$page_data = $page_result->fetch_object();
$row_num = $page_data->count;

// 페이지 번호 계산
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 목록 개수와 시작 번호 설정
$list = 10; // 한 페이지에 표시할 게시물 개수
$start_num = ($page - 1) * $list;

// 전체 페이지 계산
$total_page = ceil($row_num / $list);

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





$user_id = $_SESSION['TUID'];

// SQL 쿼리 작성
$sql = "SELECT * FROM board WHERE user_id = '$user_id'";

// 쿼리 실행
$result = $mysqli->query($sql);







// 종료일이 지난 게시물을 자동으로 삭제
$event_delete_sql = "DELETE FROM board WHERE category = 'event' AND end_date < NOW()";
$mysqli->query($event_delete_sql);

// 게시물 목록을 조회
$event_sql = "SELECT * FROM board WHERE category = 'event'";  // 이벤트 카테고리만 조회
$event_result = $mysqli->query($sql);


?>






<div class="container">
  <table class="table table-hover mb-3">
    <thead>
      <tr>
        <th scope="col"><i class="fa-solid fa-check"></i></th>
        <th scope="col">No</th>
        <th scope="col">제목</th>
        <th scope="col">글쓴이</th>
        <th scope="col">내용</th>
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
        $title1 = $data->title;
        // 제목이 길 경우 10글자로 자르기
        if(iconv_strlen($title1) > 10){
          $title1 = iconv_substr($title1, 0, 10) . '...';
        }
        ?>
      <tr>
        <th><?= (isset($_SESSION['TUID']) && $_SESSION['TUID'] == $data->user_id) ? '<input type="checkbox" id="selectAll" class="delete_checkbox form-check-input" value="' . $data->pid . '">' : '' ?></th>
        <th scope="row"><?= $data->pid ?></th>
        <td><a href="read.php?pid=<?=$data->pid?>&category=<?=$category?>"><?=$title1?> <?=$icon?></a></td>
        <td><?=$data->user_id?></td>
        <td><?=$data->content ?></td>
        <td><?=$post_date ?></td>
        <td><?=$data->likes ? $data->likes : 0 ?></td>
        <td><?=$data->hit ? $data->hit : 0 ?></td>
        <td>
        <?= (isset($_SESSION['TUID']) && $_SESSION['TUID'] == $data->user_id) ? 
          '<a href="board_modify.php?pid='.$data->pid.'&category='.$category.'"><i class="fa-regular fa-pen-to-square"></i></a>
          <a href="t_delete.php?pid='.$data->pid.'&category='.$category.'"><i class="fa-regular fa-trash-can" style="color:black;"></i></a>' 
          : ''
        ?>
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
      <li class="page-item <?= $active; ?>"><a class="page-link" href="board_list.php?category=<?=$category?>&page=<?= $i; ?>"><?= $i; ?></a></li>
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

  fetch('t_check_delete.php',{
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