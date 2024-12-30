<?php
$title = '쿠폰 상세';
// $coupon_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/admin/css/coupon.css\" rel=\"stylesheet\" >";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

$cid = $_GET['cid'];
if (!isset($cid)) {
  echo "<script>alert('쿠폰 정보가 없습니다.'); location.href = '../coupon/coupon_list.php';</script>";
}

$sql = "SELECT * FROM coupons WHERE cid = $cid";
$result = $mysqli->query($sql);
$data = $result->fetch_object();

$search_where = '';

$search_keyword = $_GET['search_keyword'] ?? '';

if($search_keyword){ 
  $search_where .= " and (coupon_name LIKE '%$search_keyword%')";
}

// 전체 데이터 개수 조회
$page_sql = "SELECT COUNT(*) AS count FROM coupons_usercp";
$page_result = $mysqli->query($page_sql);
$page_data = $page_result->fetch_assoc();
$row_num = $page_data['count'];

//페이지네이션
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 목록 개수와 시작 번호 설정
$list = 10;
$start_num = $page*$list;
$block_ct = 5;
$block_num = ceil($page/$block_ct);

$block_start = (($block_num-1)*$block_ct) + 1;
$block_end = $block_start + $block_ct - 1;

$total_page = ceil($row_num/$list);
$total_block = ceil($total_page/$block_ct);

if($block_end > $total_page ) $block_end = $total_page;


?>

<!-- 임시로 넣은 css 링크(집에서 가져온거랑 달리 연결이 안됨) -->
<head>
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/css/coupon.css?v=<?= time(); ?>">
</head>

<div class="view container">
  <ul class="list-unstyled d-flex gap-3 justify-content-end">
    <li><button class="btn btn-secondary btn-sm" id="goback">목록</button></li>
    <li><a href="coupon_edit.php?cid=<?=$cid;?>" class="btn btn-primary btn-sm">수정</a></li>
    <li><button type="button" 
            class="btn btn-danger btn-sm" 
            data-bs-toggle="modal" 
            data-bs-target="#deleteModal" 
            data-cid="<?= $data->cid ?>" 
            data-name="<?= htmlspecialchars($data->coupon_name, ENT_QUOTES) ?>" 
            data-price="<?= $data->coupon_price ? number_format($data->coupon_price).'원' : ($data->coupon_ratio ? $data->coupon_ratio."%" : "할인 없음") ?>" 
            data-dates="<?= $data->startdate.' ~ '.$data->enddate ?>">
        삭제
    </button>
</li>
  </ul>
  <!-- 쿠폰 상세정보 -->
  <div class="mt-2 p-3 border">
    <div class="row">
      <!-- 쿠폰 이미지 -->
      <div class="coupon_view_imgbox col-md-4 d-flex align-items-center justify-content-center">
        <img src="<?=$data->coupon_image;?>" alt="상세_쿠폰 이미지" class="coupon_view_img">
      </div>

      <!-- 쿠폰 정보 -->
      <div class="col-md-8">
        <table class="table table-borderless coupon_view_table">
          <tbody>
            <tr>
              <th>쿠폰번호</th>
              <td class="text-primary"><?= $data->cid; ?></td>
              <th>할인구분</th>
              <td><?= $data->coupon_type === 'fixed' ? '정액' : '정률'; ?></td>
            </tr>
            <tr>
              <th>쿠폰이름</th>
              <td><?= $data->coupon_name; ?></td>
              <th>할인율</th>
              <td><?= $data->coupon_price ? number_format($data->coupon_price).'원' : ($data->coupon_ratio ? $data->coupon_ratio."%" : "할인 없음") ?></td>
            </tr>
            <tr>
              <th>쿠폰설명</th>
              <td><?= $data->coupon_content; ?></td>
              <th>상태</th>
              <td><?= $data->status == '1' ? '활성화' : '비활성화' ?></td>
            </tr>
            <tr>
              <th>발급기간</th>
              <td colspan="3"><?= $data->startdate; ?> ~ <?= $data->enddate; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <!-- 유저 쿠폰 보유상황 -->
  <form action="" class="coupon_user_serch d-flex align-items-center justify-content-end mt-4 mb-4" id="search_form">
   <div class="us_couponlist_search d-flex gap-3 align-items-center justify-content-end">
    <select class="form-select" name="search" aria-label="사용 여부">
      <option selected>사용 여부</option>
      <option value="1" >사용</option>
      <option value="2">미사용</option>
    </select>
    <div class="d-flex justify-content-end align-items-center gap-3">
      <input type="text" class="form-control" name="search_keyword" id="search">
      <button type="submit" class="btn btn-primary" style="width:80px">검색</button>
    </div>
  </div>   
</form>

<table class="mt-3 table table-hover text-center user_couponlist">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">아이디</th>
      <th scope="col">발급일</th>
      <th scope="col">만료일</th>
      <th scope="col">사용일</th>
      <th scope="col">사용강의</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $uc_sql = "SELECT * FROM coupons_usercp ORDER BY  ucid DESC LIMIT $start_num, $list";
    $uc_result = $mysqli->query($uc_sql);
    while($uc_data = $uc_result->fetch_object()){ 
    ?>
    <tr>
      <th scope="row"><?= $uc_data->ucid ?></th>
      <td><?= $uc_data->userid ?></td>
      <td><?= $uc_data->regdate ?></td>
      <td><?= $uc_data->use_max_date ?></td>
      <td><?= $uc_data->status == 0 ? $uc_data->usedate : '미사용' ?></td> 
      <td><?= $uc_data->status == 0 ? $uc_data->reason : '' ?></td>
      <?php
      }
      ?>
  </tbody>
</table>

<nav aria-label="Page navigation">
    <ul class="pagination d-flex justify-content-center">
    <?php
      if($block_num > 1){
        $prev = $block_start - $block_ct;
        echo "<li class=\"page-item\"><a class=\"page-link\" href=\"coupons_usercp.php?search_keyword={$search_keyword}&page={$prev}\">Previous</a></li>";
      }
    ?>
    
    <?php
      for($i=$block_start; $i<=$block_end; $i++){                
        // if($page == $i) {$active = 'active';} else {$active = '';}
        $page == $i ? $active = 'active': $active = '';
    ?>
    <li class="page-item <?= $active; ?>"><a class="page-link" href="coupons_usercp.php?search_keyword=<?= $search_keyword;?>&page=<?= $i;?>"><?= $i;?></a></li>
    <?php
      }
      $next = $block_end + 1;
      if($total_block >  $block_num){
    ?>
    <li class="page-item"><a class="page-link" href="coupons_usercp.php?search_keyword=<?= $search_keyword;?>&page=<?= $next;?>">Next</a></li>
    <?php
    }         
    ?>
  </ul>
</nav>
</div>

<!-- 삭제 모달 -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal_header d-flex flex-column text-center">
                <h5 class="modal-title" id="deleteModalLabel">쿠폰 삭제 확인</h5>
              </div>
            <div class="modal-body">
                <!-- JavaScript에서 동적으로 업데이트 -->
            </div>
            <div class="modal_footer d-flex gap-3 justify-content-center">
              <a href="#" class="btn btn-danger">예</a>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">아니요</button>
            </div>
        </div>
    </div>
</div>



<script>
  $('#goback').click(function(){
    history.back();
  }); 

  document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deleteModal');
    const deleteButtons = document.querySelectorAll('button[data-bs-toggle="modal"]'); // 삭제 버튼들

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const couponId = this.getAttribute('data-cid');
            const couponName = this.getAttribute('data-name');
            const couponPrice = this.getAttribute('data-price');
            const couponDates = this.getAttribute('data-dates');

            // 모달 내용 업데이트
            const modalTitle = deleteModal.querySelector('.modal-title');
            const modalBody = deleteModal.querySelector('.modal-body');
            const confirmDeleteButton = deleteModal.querySelector('.btn-danger');

            modalTitle.textContent = `쿠폰 삭제 확인`;
            modalBody.innerHTML = `
                <p>정말로 이 쿠폰을 삭제하시겠습니까?</p>
                <ul>
                    <li>쿠폰 이름: [ ${couponName} ]</li>
                    <li>쿠폰 번호: [ ${couponId} ]</li>
                    <li>할인 금액: [ ${couponPrice} ]</li>
                    <li>사용 기간: [ ${couponDates} ]</li>
                </ul>
            `;
            confirmDeleteButton.href = `coupon_del.php?cid=${couponId}`;
        });
    });
});


</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/footer.php');
?> 
