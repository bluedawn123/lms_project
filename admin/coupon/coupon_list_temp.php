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
$list = 6;
$start_num = ($page - 1) * $list;
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
    <button class="Rows"><img src="../img/icon-img/Rows.svg" alt="박스형 리스트"></button>
    <button class="Layout"><img src="../img/icon-img/Layout.svg" alt="목차형 리스트"></button>
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

  <ul class="coupon_list layout d-flex flex-wrap justify-content-between p-0 mt-3">
  <?php 
    $sql = "SELECT * FROM coupons ORDER BY cid DESC LIMIT $start_num, $list";
    $result = $mysqli->query($sql);
    while($data = $result->fetch_object()){ 
  ?>
    <li class="coupon_item col-6 p-4">
      <img src="<?= $data->coupon_image ?>" alt="" class="col-3 thumbnail">
      <div class="ect">
        <div class="top_line mb-4">
          <div class="checkbox">
            <input type="checkbox" id="coupon<?= $data->cid ?>">
            <label for="coupon1" class="ms-2 coupon_title">No. <?= $data->cid ?> </label>
          </div>
          <p>1980.01.01 - 2999.12.31</p>
        </div>
        <div class="coupon_content">
            <h3>Welcome 회원가입</h3>
            <p class="mt-2 mb-2">회원가입 시 제공 중급 할인 쿠폰</p>
            <div class="d-flex justify-content-between align-items-center">
              <p>₩ 5,000 원</p>
              <div class="d-flex gap-2 align-items-center">
              <div class="form-check form-switch d-flex justify-content-between align-items-center">
                <input class="form-check-input" type="checkbox" role="switch" id="coupon_switchToggle" data-id="<?= $data->cid ?>" <?= $data->status ? 'checked' : '' ?>>
                <label class="form-check-label" for="coupon_switchToggle"></label>
              </div class="icon_hover">
                <a href=""><img src="../img/icon-img/Edit.svg" alt="수정" style="width: 24px;"></a>
                <a href=""><img src="../img/icon-img/Trash.svg" alt="삭제" style="width: 24px;"></a>
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
    <button class="btn btn-danger delete"><a href="coupon_del.php">삭제</a></button>
  </div>

<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/footer.php');
?> 
