<?php
$title = "학습하기";
$lecture_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/lecture.css\" rel=\"stylesheet\">";
$video_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/css/video.css\" rel=\"stylesheet\">";
$libVideo_css = "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/video.js/8.21.1/video-js.min.css\" integrity=\"sha512-eAxdlYVdHHB5//MPUJMimVOM0OoKa3I1RWCnrqvzwri3u5+RBdB6d0fdBsEOj78PyomrHR3+E3vy0ovoVU9hMQ==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\" />";
$libVideo_js = "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/video.js/8.21.1/video.min.js\" integrity=\"sha512-4ojVomDWDnx2FZyOK/eVZCTut+02zggocT1Cj8S7Y/E31ozUWlU0vZ5+rzVyy+hKZCG6Gt9RJ9elOMS70LBRtQ==\" crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/inc/header.php');

$lid = $_GET['lid'];
$vidArr = [];
$vidIndex = [];
$vidIdx = [];
$sql = "SELECT * FROM lecture_video WHERE lid = $lid ORDER BY lvid";
$result = $mysqli->query($sql);
if (isset($result)) {
  while ($data = $result->fetch_object()) {
    $vidArr[] = $data->video_lecture;
    $vidIndex[] = $data;
    $vidIdx[] = ['lvid' => $data->lvid, 'video_lecture' => $data->video_lecture]; // 영상 정보 배열
  }
} else {
  echo
  "<script>
  alert('영상이 제공되지 않는 강의 입니다');
  location.href = './lecture_view.php?lid=<?= $lid ?>';
  </script> ";
}

$lecture_sql = "SELECT * FROM lecture_list WHERE lid=$lid";
$lecture_result = $mysqli->query($lecture_sql);
if($lecture_result){
  $lecture_data = $lecture_result->fetch_object();
}

$watch_sql = "SELECT DISTINCT lid FROM lecture_watch WHERE mid = '$email' AND event_type = 'completed'";
$watch_result = $mysqli->query($watch_sql);
if($watch_result){
  $watch_data = $watch_result->fetch_object();
}

$vidArrJson = json_encode($vidArr);
$vidIdxJson = json_encode($vidIdx);
?>

<div class="hidden_hover"></div>
<div class="video">
  <div class="video_wrapper d-flex">
    <video id="lecture-video" class="video-js" width="100%" height="100%" controls>
      <source src="<?= $vidArr[0] ?>" type="video/mp4">
    </video>
    <ul class="video_index">
      <h2 class="mb-3">강의 목차</h2>
      <h3><?= $lecture_data->title ?></h3>
      <p>수강 만료일 : <?= $lecture_data->expiration_day ?></p>
      <?php 
      $complete = '';
      if(!empty($vidIndex)){
        $i = 1;
        foreach($vidIndex as $vid){
          $watch_sql = "SELECT DISTINCT lid FROM lecture_watch WHERE mid = '$email' AND event_type = 'completed' AND lvid = $vid->lvid";
          $watch_result = $mysqli->query($watch_sql);
          if($watch_result && $watch_result -> num_rows > 0){
            $complete = 'complete ';
          }
          else{
            $complete = ' ';
          }
          
      ?> 
      <li class="<?= $complete ?>" data-id = <?= $i -1 ?> ><a href="#">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M18.0303 7.96967C18.3232 8.26256 18.3232 8.73744 18.0303 9.03033L11.0303 16.0303C10.7374 16.3232 10.2626 16.3232 9.96967 16.0303L5.96967 12.0303C5.67678 11.7374 5.67678 11.2626 5.96967 10.9697C6.26256 10.6768 6.73744 10.6768 7.03033 10.9697L10.5 14.4393L16.9697 7.96967C17.2626 7.67678 17.7374 7.67678 18.0303 7.96967Z" fill="#191B1C"/>
          </svg> 
          <div>
            <h5> <?= $i ?>강 <strong><?= $vid->video_title ?></strong> </h5>
            <p><?= $vid->video_duration ?></p>
          </div>
        </a>
      </li>
       <?php
      $i++;
        }
      }
      ?>
    </ul>
    <ul class="video_desc">
      <li >
      <h2 class="mb-3">강의 설명</h2>
        <h5><?= $lecture_data->sub_title ?></h5>
        <p><?= $lecture_data->learning_obj ?></p>
      </li>
    </ul>
  </div>
  <div class="controls">
    <button id="prev-btn"><i class="fa-solid fa-backward"></i><span>이전 수업</span></button>
    <button id="next-btn"><span>다음 수업</span> <i class="fa-solid fa-forward"></i></button>
  </div>
  <aside>
    <div class="index" data-target="index">
      <img src="../img/icon-img/st_book.svg" width="40" alt="">
      <h5>강의 목차</h5>
    </div>
    <div class="desc" data-target="desc">
      <img src="../img/icon-img/ChatText.svg" width="40" alt="">
      <h5>강의 설명</h5>
    </div>
  </aside>

</div>


<script>
  // 강의 영상 배열 (PHP에서 전달)
  const vidArr = <?= $vidArrJson; ?>;
  const vidIdx = <?= $vidIdxJson; ?>;
  let currentIndex = 0; // 현재 영상 인덱스



  // 영상 요소 참조
  const video = document.getElementById('lecture-video');
  const source = video.querySelector('source');
  const prevBtn = document.getElementById('prev-btn');
  const nextBtn = document.getElementById('next-btn');
  const navBar = document.querySelector('.navbar');
  const hoverBar = document.querySelector('.hidden_hover');
  const asideBtn = document.querySelectorAll('aside > div');

  const videoWrapper = document.querySelector('.video_wrapper');
  const activeBtn = document.querySelectorAll('.video_wrapper > ul');
  const videoIndex = document.querySelectorAll('.video_index > li');



  let excuted = false;
  //헤더 호버 이벤트
  hoverBar.addEventListener('mouseenter', () => {
    setTimeout(() => {
      navBar.classList.add('active')
    }, 2000);
  })
  hoverBar.addEventListener('mouseleave', () => {
    setTimeout(() => {
      navBar.classList.remove('active')
    }, 2000);
  })

  // 영상 재생 함수
  const loadVideo = (index) => {
    if (index >= 0 && index < vidArr.length) {
      video.src = vidArr[index]; // 비디오 URL 설정
      video.play(); // 자동 재생
      currentIndex = index; // 현재 인덱스 업데이트
     
    } else {
      alert('강의가 더 이상 없습니다.');
    }
  };

  // 버튼 이벤트 핸들러
  prevBtn.addEventListener('click', () => {
    loadVideo(currentIndex - 1); // 이전 영상 로드
  });

  nextBtn.addEventListener('click', () => {
    loadVideo(currentIndex + 1); // 다음 영상 로드
  });
  videoIndex.forEach(index =>{
    
    index.addEventListener('click', (e)=>{
      e.preventDefault();
      let id = e.currentTarget.getAttribute('data-id');
      console.log(e.currentTarget);
      videoIndex.forEach(idx =>{
        idx.classList.remove('active');
      })
      index.classList.add('active');

      loadVideo(id);
    })
  })
  


  // 페이지 로드 시 첫 번째 영상 자동 재생
  if (vidArr.length > 0) {
    loadVideo(0);
  } else {
    alert('영상이 제공되지 않는 강의입니다.');
  }

  //aside 메뉴를 열기
  let currentTarget = null;
  asideBtn.forEach(btn => {
    btn.addEventListener('click', (e) => {

      e.currentTarget.classList.add('active');
      const target = btn.getAttribute("data-target");
      activeBtn.forEach(item =>{

        item.classList.remove('active');
      })
      videoWrapper.querySelector('.video_' + target).classList.add('active');

      if (currentTarget === target) {
        // 같은 메뉴를 클릭했을 때 닫기
        e.currentTarget.classList.remove('active');

        btn.closest('.video').firstElementChild.classList.remove('open');
        currentTarget = null;
      } else {
        // 다른 메뉴를 클릭했을 때
        currentTarget = target;
        asideBtn.forEach(btn => {
          btn.classList.remove('active');
        })
        e.currentTarget.classList.add('active');
        btn.closest('.video').firstElementChild.classList.add('open')
      }
    })
  })
  
  const getCurrentLvid = () => vidIdx[currentIndex].lvid;

  // 비디오가 시작되면 sendWatchEvent 함수 실행
  video.addEventListener('play', () => {
    console.log('Video started');
    sendWatchEvent('start');
  });
  /*
 // 비디오가 멈추면 sendWatchEvent 함수 실행
  video.addEventListener('pause', () => {
    console.log('Video paused');
    sendWatchEvent('pause');
  });*/
 // 비디오가 끝나면 sendWatchEvent 함수 실행
  video.addEventListener('ended', () => {
    console.log('Video ended');
    sendWatchEvent('completed');
  });

  const sendWatchEvent = (eventType) => {
    const lid = '<?= $lid ?>'; // 강의 ID
    const mid = '<?= $email ?>'; // 사용자 ID
    const lvid = getCurrentLvid();
    let data = JSON.stringify({
        mid: mid,
        lid: lid,
        lvid: lvid,
        eventType: eventType,
        timestamp: new Date().toISOString(),
      });
      console.log(data);
    fetch('lecture_videoCheck.php', {
      method: 'POST',
      body:data ,
    }).then((response) => response.json())
    .then((result) => {
      if (result.status === 'success') {
        console.log(`LVID ${lvid} 이벤트 저장 성공: ${eventType}`);
      } else {
        console.error(`LVID ${lvid} 이벤트 저장 실패:`, result.message);
      }
    })
    .catch((error) => console.error('네트워크 오류:', error));
  };
  

  videojs(video, {
    controls: true,
    autoplay: false,
    preload: 'auto',
    muted: true,
  });
</script>