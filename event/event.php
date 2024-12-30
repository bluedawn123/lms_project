<?php 
$title = "EVENT";
$community_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/community.css\" rel=\"stylesheet\">";

include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');

?>

<div class="event_box">
  <div class="container">
    <h2><?= $title ?></h2>  
    <p>지금 진행중인 다채로운 혜택 모음!<br>두 눈으로 확인하세요.</p>  
  </div>  
</div>
<style>
  .event_box .container{
  padding-top:60px;
  padding-bottom: 60px;
}
</style>
<div class="community container">
  <div class="row">
    <div class="event content col-12">
      <!-- 탭 네비게이션 -->
      <ul class="nav nav-tabs mb-3" id="eventTabs" role="tablist" style="gap: 0.5rem;">
        <li class="nav-item" role="presentation">
          <button class="nav-link active px-3 py-2 small text-dark" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true" style="font-size: 0.9rem;">전체</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link px-3 py-2 small text-dark" id="recommended-tab" data-bs-toggle="tab" data-bs-target="#recommended" type="button" role="tab" aria-controls="recommended" aria-selected="false" style="font-size: 0.9rem;">추천 이벤트</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link px-3 py-2 small text-dark" id="imminent-tab" data-bs-toggle="tab" data-bs-target="#imminent" type="button" role="tab" aria-controls="imminent" aria-selected="false" style="font-size: 0.9rem;">종료 임박</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link px-3 py-2 small text-dark" id="ended-tab" data-bs-toggle="tab" data-bs-target="#ended" type="button" role="tab" aria-controls="ended" aria-selected="false" style="font-size: 0.9rem;">종료</button>
        </li>
      </ul>

      <!-- 탭 콘텐츠 -->
      <div class="tab-content p-3 border rounded bg-light shadow-sm" id="eventTabsContent">
        <!-- 전체 -->
        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
          <ul class="list-unstyled">
            <li><img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/img/event/001.png" alt=""></li>
            <li><img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/img/event/002.png" alt=""></li>
            <li><img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/img/event/003.png" alt=""></li>
          </ul>
        </div>

        <!-- 추천 이벤트 -->
        <div class="tab-pane fade" id="recommended" role="tabpanel" aria-labelledby="recommended-tab">
          <ul class="list-unstyled">
            <li><img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/img/event/004.png" alt=""></li>
            <li><img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/img/event/005.png" alt=""></li>
            <li><img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/img/event/006.png" alt=""></li>
          </ul>
        </div>

        <!-- 종료 임박 -->
        <div class="tab-pane fade" id="imminent" role="tabpanel" aria-labelledby="imminent-tab">
          <ul class="list-unstyled">
            <li><img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/img/event/007.png" alt=""></li>
            <li><img src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/qc/img/event/008.png" alt=""></li>
          </ul>
        </div>

        <!-- 종료 -->
        <div class="tab-pane fade" id="ended" role="tabpanel" aria-labelledby="ended-tab">
          <h5 class="fw-bold mb-3 text-muted">종료된 이벤트</h5>
          <ul class="list-unstyled text-secondary">
            <li>✅ 신년 감사 이벤트 종료</li>
            <li>✅ 첫 구매 감사 쿠폰 제공 종료</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/footer.php');
?>