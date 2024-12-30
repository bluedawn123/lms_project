<?php
$title = '쿠폰 목록';
$coupon_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/coupon.css\" rel=\"stylesheet\" >";
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/header.php');

$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;

$search_where = '';

$search_keyword = $_GET['search_keyword'] ?? '';

if($search_keyword){ 
  $search_where .= " and (coupon_name LIKE '%$search_keyword%')";
}

// 전체 데이터 개수 조회
$page_sql = "SELECT COUNT(*) AS count FROM coupons";
$page_result = $mysqli->query($page_sql);
$page_data = $page_result->fetch_assoc();
$row_num = $page_data['count'];

//페이지네이션
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 목록 개수와 시작 번호 설정
$list = 10;
$start_num = ($page - 1) * $list;
$layout_list = 6;
$layout_start_num = ($page - 1) * $layout_list;
$block_ct = 5;
$block_num = ceil($page/$block_ct);

$block_start = (($block_num-1)*$block_ct) + 1;
$block_end = $block_start + $block_ct - 1;

$total_page = ceil($row_num/$list);
$total_block = ceil($total_page/$block_ct);

if($block_end > $total_page ) $block_end = $total_page;

?>

<form action="" class="coupon_serch d-flex align-items-center justify-content-between" id="search_form">
  <div class="couponlist_view d-flex">
    <button class="Rows active">
    <svg width="32" height="32" viewBox="0 0 32 32" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
      <path d="M25.6891 5.27782C19.245 4.64763 12.7548 4.64763 6.31065 5.27782C5.73102 5.33451 5.24293 5.73203 5.06458 6.28145C4.35347 8.472 4.35347 10.8616 5.06458 13.0521C5.24293 13.6015 5.73102 13.9991 6.31065 14.0558C12.7548 14.6859 19.245 14.6859 25.6891 14.0558C26.2687 13.9991 26.7568 13.6015 26.9352 13.0521C27.6463 10.8616 27.6463 8.472 26.9352 6.28145C26.7568 5.73203 26.2687 5.33451 25.6891 5.27782Z" fill="#E2E6E9"/>
      <path d="M25.6891 17.9445C19.245 17.3143 12.7548 17.3143 6.31065 17.9445C5.73102 18.0012 5.24293 18.3987 5.06458 18.9481C4.35347 21.1387 4.35347 23.5282 5.06458 25.7188C5.24293 26.2682 5.73102 26.6657 6.31065 26.7224C12.7548 27.3526 19.245 27.3526 25.6891 26.7224C26.2687 26.6657 26.7568 26.2682 26.9352 25.7188C27.6463 23.5282 27.6463 21.1387 26.9352 18.9481C26.7568 18.3987 26.2687 18.0012 25.6891 17.9445Z" fill="#E2E6E9"/>
      </svg>
    </button>
    <button class="Layout">
      <svg width="32" height="32" viewBox="0 0 32 32" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path d="M12.0902 4.304C10.2722 4.10081 8.39349 4.10081 6.57551 4.304C5.40593 4.43472 4.46019 5.35572 4.32227 6.53497C4.10482 8.3942 4.10482 10.2724 4.32227 12.1317C4.46019 13.3109 5.40593 14.2319 6.57551 14.3626C8.39349 14.5658 10.2722 14.5658 12.0902 14.3626C13.2598 14.2319 14.2055 13.3109 14.3434 12.1317C14.5609 10.2724 14.5609 8.39419 14.3434 6.53497C14.2055 5.35572 13.2598 4.43472 12.0902 4.304Z" fill="#E2E6E9"/>
        <path d="M12.0902 17.6373C10.2722 17.4341 8.39349 17.4341 6.57551 17.6373C5.40593 17.7681 4.46019 18.6891 4.32227 19.8683C4.10482 21.7275 4.10482 23.6058 4.32227 25.465C4.46019 26.6442 5.40593 27.5652 6.57551 27.696C8.39349 27.8991 10.2722 27.8991 12.0902 27.696C13.2598 27.5652 14.2055 26.6442 14.3434 25.465C14.5609 23.6058 14.5609 21.7275 14.3434 19.8683C14.2055 18.6891 13.2598 17.7681 12.0902 17.6373Z" fill="#E2E6E9"/>
        <path d="M25.4236 4.304C23.6056 4.10081 21.7268 4.10081 19.9089 4.304C18.7393 4.43472 17.7935 5.35572 17.6556 6.53497C17.4382 8.3942 17.4382 10.2724 17.6556 12.1317C17.7935 13.3109 18.7393 14.2319 19.9089 14.3626C21.7268 14.5658 23.6056 14.5658 25.4236 14.3626C26.5931 14.2319 27.5389 13.3109 27.6768 12.1317C27.8942 10.2724 27.8942 8.39419 27.6768 6.53497C27.5389 5.35572 26.5931 4.43472 25.4236 4.304Z" fill="#E2E6E9"/>
        <path d="M25.4236 17.6373C23.6056 17.4341 21.7268 17.4341 19.9089 17.6373C18.7393 17.7681 17.7935 18.6891 17.6556 19.8683C17.4382 21.7275 17.4382 23.6058 17.6556 25.465C17.7935 26.6442 18.7393 27.5652 19.9089 27.696C21.7268 27.8991 23.6056 27.8991 25.4236 27.696C26.5931 27.5652 27.5389 26.6442 27.6768 25.465C27.8942 23.6058 27.8942 21.7275 27.6768 19.8683C27.5389 18.6891 26.5931 17.7681 25.4236 17.6373Z" fill="#E2E6E9"/>
      </svg>
    </button>
  </div>

 <div class="couponlist_search d-flex gap-3">
    <select class="form-select" name="search_cat" aria-label="할인 구분 선택">
      <option selected>할인 구분</option>
      <option value="1">정액</option>
      <option value="2">정률</option>
    </select>
    <select class="form-select" name="search_cat" aria-label="카테고리 선택">
      <option selected>활성화 구분</option>
      <option value="1" >활성화</option>
      <option value="2">비활성화</option>
    </select>
    <div class="d-flex justify-content-end gap-3">
      <input type="text" class="form-control" name="search_keyword" id="search">
      <button type="submit" class="btn btn-primary">검색</button>
    </div>   
 </div>   
  </form>

<table class="mt-3 table table-hover text-center couponlist">
  <thead>
    <tr>
      <th scope="col"><input type="checkbox" id="selectAll"></th>
      <th scope="col">No</th>
      <th scope="col" style="width: 35%;">쿠폰 이름</th>
      <th scope="col" style="width: 10%;">할인율</th>
      <th scope="col">발급기간</th>
      <th scope="col">활성화</th>
      <th scope="col">수정 및 삭제</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $sql = "SELECT * FROM coupons ORDER BY cid DESC LIMIT $start_num, $list";
    $result = $mysqli->query($sql);
    while($data = $result->fetch_object()){ 
    ?>
    <tr>
      <td scope="row">
      <input type="checkbox" class="coupon-check" data-cid="<?= $data->cid; ?>" data-name="<?= htmlspecialchars($data->coupon_name, ENT_QUOTES); ?>">
      </td>
      <th scope="row">
        <input type="hidden" name="cid[]" value="<?= $data->cid; ?>">
        <?= $data->cid; ?>
      </th>
      <td>
        <a href="coupon_view.php?cid=<?= $data->cid; ?>"><?= $data->coupon_name ?></a>
      </td>
      <td><?= $data->coupon_price ? number_format($data->coupon_price).'원' : ($data->coupon_ratio ? $data->coupon_ratio."%" : "무료") ?></td>
      <td><?= $data->	startdate.'~'.$data->	enddate; ?> </td>
      <td>
      <div class="form-check form-switch d-flex justify-content-center align-items-center">
        <input class="form-check-input" 
          type="checkbox" 
          role="switch" 
          data-id="<?= $data->cid ?>" 
          id="coupon_switchToggle<?= $data->cid ?>" 
          <?= $data->status ? 'checked' : '' ?>>
        <label class="form-check-label" for="coupon_switchToggle<?= $data->cid ?>"></label>
      </div>
      </td>
      <td class="icon_hover d-flex gap-1 justify-content-center">
        <a href="coupon_edit.php?cid=<?= $data->cid; ?>">
          <img src="../img/icon-img/Edit.svg" alt="수정">
        </a>
        <button type="button" 
          class="delete-btn" 
          data-bs-toggle="modal" 
          data-bs-target="#deleteModal" 
          data-cid="<?= $data->cid; ?>" 
          data-name="<?= htmlspecialchars($data->coupon_name, ENT_QUOTES); ?>" 
          data-price="<?= $data->coupon_price ? '₩ '.number_format($data->coupon_price) : ($data->coupon_ratio ? $data->coupon_ratio.'%' : '무료'); ?>" 
          data-dates="<?= $data->startdate.' ~ '.$data->enddate; ?>">
          <img src="../img/icon-img/Trash.svg" alt="삭제" style="width: 22px;">
        </button>
      </td>
      <?php
      }
      ?>
  </tbody>
</table>

<ul class="coupon_list layout flex-wrap justify-content-between p-0 mt-3">
  <?php 
    $sql = "SELECT * FROM coupons ORDER BY cid DESC LIMIT $layout_start_num, $layout_list";
    $result = $mysqli->query($sql);
    while($data = $result->fetch_object()){ 
  ?>
  <li class="coupon_item col-6 p-4">
    <img src="<?= $data->coupon_image ?>" alt="" class="col-3 thumbnail">
    <div class="ect">
      <div class="top_line mb-4">
        <div class="checkbox">
          <input type="checkbox" class="coupon-check" data-cid="<?= $data->cid; ?>" data-name="<?= htmlspecialchars($data->coupon_name, ENT_QUOTES); ?>">
          <label for="coupon1" class="ms-2 coupon_title">No. <?= $data->cid ?> </label>
        </div>
        <p><?= $data->	startdate.' - '.$data->	enddate; ?></p>
      </div>
      <div class="coupon_content">
          <a class="name" href="coupon_view.php?cid=<?= $data->cid; ?>"><?= $data->coupon_name ?></a>
          <p class="mt-2 mb-2"><?= $data->coupon_content ?></p>
          <div class="d-flex justify-content-between align-items-center">
            <p><?= $data->coupon_price ? '₩ '.number_format($data->coupon_price) : ($data->coupon_ratio ? $data->coupon_ratio." %" : "무료") ?></p>
              <div class="d-flex gap-2 align-items-center">
                <div class="form-check form-switch d-flex justify-content-center align-items-center">
                  <input class="form-check-input" 
                    type="checkbox" 
                    role="switch" 
                    data-id="<?= $data->cid ?>" 
                    id="coupon_switchToggle<?= $data->cid ?>" 
                    <?= $data->status ? 'checked' : '' ?>>
                  <label class="form-check-label" for="coupon_switchToggle<?= $data->cid ?>"></label>
                </div>
                <div class="icon_hover">
                  <a href="coupon_edit.php?cid=<?= $data->cid; ?>">
                    <img src="../img/icon-img/Edit.svg" alt="수정" style="width: 22px;">
                  </a>
                  <button type="button" 
                    class="delete-btn" 
                    data-bs-toggle="modal" 
                    data-bs-target="#deleteModal" 
                    data-cid="<?= $data->cid; ?>" 
                    data-name="<?= htmlspecialchars($data->coupon_name, ENT_QUOTES); ?>" 
                    data-price="<?= $data->coupon_price ? '₩ '.number_format($data->coupon_price) : ($data->coupon_ratio ? $data->coupon_ratio.'%' : '무료'); ?>" 
                    data-dates="<?= $data->startdate.' ~ '.$data->enddate; ?>">
                    <img src="../img/icon-img/Trash.svg" alt="삭제" style="width: 22px;">
                  </button>
                </div>
              </div>
          </div>
      </div>
    </div>
  </li>
  <?php
    }
  ?>
</ul>

<nav aria-label="Page navigation">
    <ul class="pagination">
    
    <?php
      if ($block_num > 1) { //prev 버튼
        $prev = $block_start - $block_ct;
        echo "<li class=\"page-item prev\">
            <a class=\"page-link\" href=\"coupon_list.php?search_keyword={$search_keyword}&page={$prev}\">
                <img src=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/img/icon-img/CaretLeft.svg\" alt=\"페이지네이션 prev\">
            </a>
        </li>";
      }
    ?>
    
    <?php
      for($i=$block_start; $i<=$block_end; $i++){                
        // if($page == $i) {$active = 'active';} else {$active = '';}
        $page == $i ? $active = 'active': $active = '';
    ?>
    <li class="page-item <?= $active; ?>">
      <a class="page-link" href="coupon_list.php?search_keyword=<?= $search_keyword;?>&page=<?= $i;?>">
        <?= $i;?>
      </a>
    </li>
  
    <?php
      }
      $next = $block_end + 1;
      if($total_block >  $block_num){ //next 버튼
    ?>
    <li class="page-item next">
      <a class="page-link" href="coupon_list.php?search_keyword=<?= $search_keyword;?>&page=<?= $next;?>">
        <img src="http://<?= $_SERVER['HTTP_HOST'] ?>/qc/admin/img/icon-img/CaretRight.svg" alt="페이지네이션 next">
      </a>
    </li>
    <?php
    }         
    ?>
  </ul>
</nav>

  <div class="d-flex gap-3 justify-content-end">
    <button class="btn btn-secondary"><a href="coupon_copy.php">복사</a></button>
    <button class="btn btn-primary"><a href="coupon_regis.php">생성</a></button>
    <button class="btn btn-danger" id="bulkDeleteBtn" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal">삭제</button>
  </div>

<!-- 개별삭제 모달 -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal_header d-flex flex-column text-center">
              <h5 class="modal-title" id="deleteModalLabel">쿠폰 삭제 확인</h5>
              <p class="mt-2 mb-0">이 쿠폰을 삭제하시겠습니까? 삭제된 쿠폰은 복구할 수 없습니다.</p>
            </div>
            <div class="modal-body">
                <!-- JavaScript가 여기에 데이터를 삽입 -->
            </div>
            <div class="modal_footer d-flex gap-3 justify-content-center">
              <a href="coupon_del.php" class="btn btn-danger">예</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">아니요</button>
            </div>
        </div>
    </div>
</div>

<!-- 일괄 삭제 모달 -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal_header d-flex flex-column text-center">
        <h5 class="modal-title" id="bulkDeleteModalLabel">선택한 쿠폰 삭제 확인</h5>
        <p class="mt-2 mb-0">아래 쿠폰을 삭제하시겠습니까? 삭제된 쿠폰은 복구할 수 없습니다.</p>
      </div>
      <div class="modal-body">
        <ul id="selectedCouponsList"></ul>
      </div>
      <div class="modal_footer d-flex gap-3 justify-content-center">
        <button type="button" id="confirmBulkDelete" class="btn btn-danger">예</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">아니요</button>
      </div>
    </div>
  </div>
</div>

  <script>
  document.addEventListener('DOMContentLoaded', function () {

  //레이어 뷰 변수
  const rowsButton = document.querySelector('.Rows');
  const layoutButton = document.querySelector('.Layout');
  const tableView = document.querySelector('.couponlist'); // table
  const ulView = document.querySelector('.coupon_list'); // ul

  // 초기 상태 설정
  function initializeView() {
    tableView.classList.add('active'); // table 활성화
    ulView.classList.remove('active'); // ul 비활성화
  }
  // Rows 버튼 레이어뷰
  rowsButton.addEventListener('click', (e)=>{
    e.preventDefault();
    tableView.classList.add('active');
    ulView.classList.remove('active');
    rowsButton.classList.add('active');
    layoutButton.classList.remove('active');
  });
  // Layout버튼 박스뷰
  layoutButton.addEventListener('click', (e)=>{
    e.preventDefault();
    ulView.classList.add('active');
    tableView.classList.remove('active');
    layoutButton.classList.add('active');
    rowsButton.classList.remove('active');
  });
  // 초기 상태 실행
  initializeView();


  //개별삭제 모달 변수
  const deleteButtons = document.querySelectorAll('.delete-btn'); 
  const modalBody = document.querySelector('#deleteModal .modal-body'); 
  const confirmDelete = document.querySelector('#deleteModal .btn-danger');
  
  //일괄삭제 모달 변수
  const selectAllCheckbox = document.getElementById('selectAll');
  const couponCheckboxes = document.querySelectorAll('.coupon-check');
  const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
  const confirmBulkDelete = document.getElementById('confirmBulkDelete');
  const selectedCouponsList = document.getElementById('selectedCouponsList');

  // 전체 선택/해제 기능
  selectAllCheckbox.addEventListener('change', function () {
      const isChecked = this.checked;
      couponCheckboxes.forEach(checkbox => {
          checkbox.checked = isChecked;
      });
  });

  // 개별삭제 모달 스크립트
  deleteButtons.forEach(button => {
    button.addEventListener('click', function () {
      const couponId = this.getAttribute('data-cid');
      const couponName = this.getAttribute('data-name');
      const couponPrice = this.getAttribute('data-price');
      const couponDates = this.getAttribute('data-dates');
      modalBody.innerHTML = `
          <ul>
              <li>쿠폰 번호 : [ ${couponId} ]</li>
              <li>쿠폰 이름 : [ ${couponName} ]</li>
              <li>할인 금액 : [ ${couponPrice} ]</li>
              <li>사용 기간 : [ ${couponDates} ]</li>
          </ul>
      `;
      confirmDelete.setAttribute('href', `coupon_del.php?cid=${couponId}`);
    });
  });

  // 일괄삭제 모달 스크립트
  bulkDeleteBtn.addEventListener('click', function () {
    const selectedCoupons = Array.from(couponCheckboxes)
      .filter(checkbox => checkbox.checked)
      .map(checkbox => ({
        id: checkbox.getAttribute('data-cid'),
        name: checkbox.getAttribute('data-name')
      }));
    if (selectedCoupons.length === 0) {
      alert('삭제할 쿠폰을 선택하세요.');
      return;
    }

    selectedCouponsList.innerHTML = selectedCoupons
      .map(coupon => `<li>${coupon.name} (ID: ${coupon.id})</li>`)
      .join('');

    confirmBulkDelete.dataset.ids = JSON.stringify(selectedCoupons.map(coupon => coupon.id));
  });

  confirmBulkDelete.addEventListener('click', function () {
    const idsToDelete = JSON.parse(this.dataset.ids || '[]');

      if (idsToDelete.length === 0) {
          alert('삭제할 쿠폰이 없습니다.');
          return;
      }

      fetch('coupon_bulk_delete.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ ids: idsToDelete })
      })
      .then(response => {
        return response.json();
      })
      .then(data => {
          if (data.success) {
              alert('선택한 쿠폰이 삭제되었습니다.');
              location.reload();
          } else {
              alert(data.message || '삭제 실패');
          }
      })
      .catch(error => {
          console.error('Fetch 오류:', error);
          alert('삭제 중 문제가 발생했습니다.');
      });
    });
  });

  // 활성화 스위치 토글 선택
  const toggleSwitches = document.querySelectorAll('.form-check-input'); 
  toggleSwitches.forEach(switchToggle => {
    switchToggle.addEventListener('change', function () {
      const couponId = this.getAttribute('data-id'); // 쿠폰 ID 가져오기
      const newStatus = this.checked ? 1 : 0; // 활성화 상태: 체크 여부에 따라 값 설정

      // 서버로 상태 업데이트 요청
      fetch('toggle_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cid: couponId, status: newStatus })
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
            alert(`쿠폰 ID ${couponId} 상태가 ${newStatus ? '활성화' : '비활성화'}로 변경되었습니다.`);
          } else {
            alert('상태 변경에 실패했습니다. 다시 시도해주세요.');
            // 스위치 상태를 되돌림
            this.checked = !this.checked;
          }
      })
      .catch(error => {
        console.error('에러 발생:', error);
        alert('서버와의 통신 중 문제가 발생했습니다.');
        // 스위치 상태를 되돌림
        this.checked = !this.checked;
      });
    });
  });


</script>




<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/footer.php');
?> 
