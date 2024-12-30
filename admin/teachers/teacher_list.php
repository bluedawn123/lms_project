<?php
$title = "강사 목록";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');


// if(!isset($_SESSION['AUID'])){
//   echo "
//     <script>
//       alert('관리자로 로그인해주세요');
//       location.href = '../login.php';
//     </script>
//   ";
// }

//검색
$search_where = ''; //초기화
$search_keyword = $_GET['search_keyword'] ?? '';

if($search_keyword){ 
  // $search_where .= " and (name LIKE '%$search_keyword%' OR content LIKE '%$search_keyword%')";
  $search_where .= " and (name LIKE '%$search_keyword%')";
}

//데이터의 개수 조회
$page_sql = "SELECT COUNT(*) AS cnt FROM teachers WHERE 1=1 $search_where";
$page_result = $mysqli->query($page_sql);
$page_data = $page_result->fetch_assoc();

//print_r($page_data); Array ( [cnt] => 22 )

$row_num = $page_data['cnt'];  //echo $row_num; 22


//페이지네이션 
if(isset($_GET['page'])){
  $page = $_GET['page'];
}else{
  $page = 1;
}

$list = 10;
$start_num=($page-1)*$list;
$block_ct = 5;
$block_num = ceil($page/$block_ct); //$page1/5 0.2 = 1

$block_start = (($block_num-1)*$block_ct) + 1;
$block_end = $block_start + $block_ct - 1;

$total_page = ceil($row_num/$list); //총75개 10개씩, 8
$total_block = ceil($total_page/$block_ct);

if($block_end > $total_page ) $block_end = $total_page;

//목적에 맞게 목록 가져오기



$ordertype = $_GET['orderby'] ?? 'ASC';

if(isset($_GET['orderby'])){
  $orderColumn = 'year_sales' ;
}else{
  $orderColumn = 'tid' ;
}

// $sql = "SELECT * FROM teachers WHERE 1=1 $search_where ORDER BY $orderColumn $ordertype LIMIT $start_num, $list"; 
// $result = $mysqli->query($sql); //쿼리 실행 결과
// while($data = $result->fetch_object()){
//   $dataArr[] = $data;
// }


$join_sql = "SELECT 
    t.*,
    COUNT(l.t_id) AS lecture_count,
    COUNT(l.t_id) AS lecture_num
FROM 
    teachers t
LEFT JOIN 
    lecture_list l
ON 
    t.id = l.t_id
WHERE 1=1 $search_where 
GROUP BY 
    t.id
ORDER BY $orderColumn $ordertype LIMIT $start_num, $list";
// echo $join_sql;

$join_result = $mysqli->query($join_sql);
while($join_data = $join_result->fetch_object()){
  $dataArr[] = $join_data;
}


?>

<div class="container">
  <form action="">
    <h4>현재 강사 수 : <?= $row_num; ?> 명</h4>
    <div class="d-flex gap-3 w-30 mt-3 align-items-center">
    <tr>
        <td colspan="3">
          <div class="d-flex gap-3">
            <select class="form-select mt-3" id="sort_order" >
              <option value="base" selected>정렬 기준을 선택해 주세요</option>
              <option value="desc">매출 많은 순</option>
              <option value="asc">매출 적은 순</option>
              <!-- <option value="highLectureToLow">강의 많은 순</option>
              <option value="lowLectureToHigh">강의 적은 순</option> -->
            </select>
          </div>
        </td>
      </tr>
      <input type="text" class="form-control w-25 ms-auto" name="search_keyword" id="search">
      <button class="btn btn-primary btn-sm w-20">검색</button>
    </div>     
    <hr> 
    
    <!-- <form action="plist_update.php" method="GET"> -->
    <table class="table">
      <thead>
        <tr>
          <th scope="col">No. </th>
          <th scope="col">이름</th>
          <th scope="col">아이디</th>
          <th scope="col">이메일</th>
          <th scope="col">가입날짜</th>
          <th scope="col">강의 갯수</th>
          <th scope="col">올해 매출</th>
          <th scope="col">강사 등급</th>
          <th scope="col">상세보기</th>
          <th scope="col">쪽지 보내기</th>


        </tr>
      </thead>
      <tbody>
          <?php
            if(isset($dataArr)){
              foreach($dataArr as $item){
          ?> 
          <tr>
            <th scope="row"><?= $item->tid; ?></th>
              <td><?= $item->name; ?></td>
              <td><?= $item->id; ?></td>
              <td><?= $item->email; ?></td>
              <td><?= $item->reg_date; ?></td>
              <td><?= $item->lecture_num; ?></td>
              <td><?= $item->year_sales; ?></td>
              <td><?= $item->grade; ?></td>
              <td><a href="teacher_view.php?tid=<?= $item->tid;?>" class="btn btn-primary btn-sm">상세보기</a></td>
              <td>
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#messageModal" data-mid="<?= $item->tid; ?>" >쪽지보내기</button>
              </td>
          </tr>

          <?php
              }
            }
          ?> 
      </tbody>
    </table>
  </form>
  <nav aria-label="Page navigation">
      <ul class="pagination d-flex justify-content-center">
        <?php
          if($block_num > 1){
            $prev = $block_start - $block_ct;
            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"teacher_list.php?&search_keyword={$search_keyword}&page={$prev}\">Previous</a></li>";
          }
        ?>
        
        <?php
          for($i=$block_start; $i<=$block_end; $i++){                
            $page == $i ? $active = 'active': $active = '';
        ?>
        <li class="page-item <?= $active; ?>"><a class="page-link" href="teacher_list.php?&search_keyword=<?= $search_keyword;?>&page=<?= $i;?>"><?= $i;?></a></li>
        <?php
          }
          $next = $block_end + 1;
          if($total_block >  $block_num){
        ?>
        <li class="page-item"><a class="page-link" href="teacher_list.php?&search_keyword=<?= $search_keyword;?>&page=<?= $next;?>">Next</a></li>
        <?php
        }         
        ?>
      </ul>
    </nav>
</div>
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="messageModalLabel">쪽지 보내기</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="sendMessageForm" method="post">
          <!-- 수정: sender_idx는 관리자 정보를 세션에서 가져오므로 유지 -->
          <input type="hidden" id="sender_idx" name="sender_idx" value="<?= $_SESSION['AUIDX'] ?>"> 
          <input type="hidden" id="sender_name" name="sender_name" value="<?= $_SESSION['AUNAME'] ?>"> 
          <!-- 수정: receiver_tid는 JavaScript로 설정되므로 기본값을 제거 -->
          <input type="hidden" id="receiver_tid" name="receiver_tid" value=""> 
          <div class="mb-3">
            <label for="message" class="form-label">메시지 내용</label>
            <textarea id="message" name="message" class="form-control" placeholder="쪽지 내용을 입력하세요" rows="10" maxlength="2000" required></textarea>
            <small class="form-text text-muted">최대 2000자까지 입력할 수 있습니다.</small>
          </div>
          <button type="submit" class="btn btn-primary">보내기</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    const sortSelect = document.getElementById('sort_order');

    sortSelect.addEventListener('change', function () {
        const selectedValue = this.value;
        location.href= `?orderby=${selectedValue}`;
    });

    //쪽지 보내기
    document.getElementById("messageModal").addEventListener("show.bs.modal", function (event) {
    const button = event.relatedTarget; // 버튼에서 data-mid 가져오기
    const receiverTid = button.getAttribute("data-mid"); // data-mid 값 가져오기
    document.getElementById("receiver_tid").value = receiverTid; // 숨겨진 input에 설정
    });

// 메시지 전송 처리
document.getElementById("sendMessageForm").addEventListener("submit", function (e) {
    e.preventDefault(); // 기본 폼 전송 막기

    // 폼 데이터 가져오기
    const sender_idx = document.getElementById("sender_idx").value;
    const sender_name = document.getElementById("sender_name").value;
    const receiver_tid = document.getElementById("receiver_tid").value;
    const message = document.getElementById("message").value;

    // POST 요청 보내기
    fetch("send_message.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ 
        sender_idx: sender_idx, 
        sender_name: sender_name, 
        receiver_tid: receiver_tid, 
        message: message 
        })
    })
    .then(response => response.json()
    )
    .then(data => {
      console.log(data);
        if (data.status === "success") {
            alert(data.message); // 성공 메시지 표시
            const modal = bootstrap.Modal.getInstance(document.getElementById("messageModal"));
            modal.hide(); // 모달 닫기
        } else {
            alert(data.message); // 오류 메시지 표시
        }
    })
    .catch(error => console.error("Error:", error));
});
</script>


<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/footer.php');
?>


