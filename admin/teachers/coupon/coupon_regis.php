<?php
$title = '쿠폰 등록';
$coupon_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/coupon.css\" rel=\"stylesheet\" >";
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

// if(!isset($_SESSION['AUID'])){
//   echo "
//     <script>
//       alert('관리자로 로그인해주세요');
//       location.href = '../login.php';
//     </script>
//   ";
// }


?>

<div class="coupon_regis container">
  <form action="coupon_regis_ok.php" id="coupon_submit" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="coupon_imageId" id="coupon_imageId" value="">
  <input type="hidden" id="coupon_description" name="coupon_description" value="">
  
    <div class="row coupon">
      <div class="col-4 mb-5">
        <h6>쿠폰 이미지 등록</h6>
          <div class="coupon_regisImg d-flex justify-content-center align-items-center mb-3">
            <img src="../img/icon-img/no-image.png" id="coverImg" alt="">
          </div>
          <div class="input-group">
            <input type="file" class="form-control" accept="image/*" name="coupon_image" id="coupon_image">
          </div>
      </div>

      <div class="col-8 mt-3">
        <table class="table">
          <thead class="visually-hidden">
            <tr>
              <th scope="col">구분</th>
              <th scope="col">내용</th>
            </tr>
          </thead>
          <tbody>
            <tr scope="row">
              <th scope="row" class="insert_name">쿠폰 이름</th>
              <td colspan="3">
                <input type=" text" class="form-control" name="coupon_name" id="coupon_name" required>
              </td>
            </tr>
            <tr scope="row">
              <th scope="row" class="insert_name">쿠폰 설명</th>
              <td colspan="3">
              <textarea class="form-control" name="coupon_content" id="coupon_content" rows="2"></textarea>
            </tr>
            <tr>
              <th scope="row">할인구분</th>
              <td>
                <select class="form-select" name="coupon_type" id="coupon_type" aria-label="할인구분">                            
                  <option value="fixed" selected>정액</option>
                  <option value="percentage">정률</option>
                </select>
              </td>
              <td id="ct1">
                <div class="input-group">
                  <input type="text" name="coupon_price" class="form-control" aria-label="할인가" value="0" aria-describedby="coupon_price"> 
                  <span class="input-group-text" id="coupon_price">원</span>
                </div>
              </td> 
              <td id="ct2">
                <div class="input-group">
                  <input type="text" name="coupon_ratio" class="form-control" aria-label="할인비율" value="0" aria-describedby="coupon_ratio">
                  <span class="input-group-text" id="coupon_ratio">%</span>
                </div>
              </td>
            </tr> 
            <tr>
              <th scope="row">사용기한</th>
              <td colspan="3">
                <div class="d-flex gap-2">
                  <input type="date" class="form-control" name="startdate" id="startdate" placeholder="" required>
                  <input type="date" class="form-control" name="enddate" id="enddate" placeholder="" required>
                </div>
              </td>
            </tr>
            <tr scope="row">
              <th scope="row" class="insert_name">활성화</th>
              <td colspan="3">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="coupon_activate" id="coupon_activate" value="1">
                  <label class="form-check-label" for="inlineRadio1">활성화</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="coupon_deactivate" id="coupon_deactivate" value="0">
                  <label class="form-check-label" for="inlineRadio2">비활성화</label>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="gap-3 mt-50 d-flex justify-content-end">
      <button type="submit" class="btn btn-primary">등록</button>
      <button href="coupon_list.php" class="btn btn-danger cancel">취소</button>
    </div>
  </form>
</div>
  
<script>
  //이미지 구현창
  function addCover(file, cover) {
    let coverImage = file;
    coverImage.on('change', (e) => {
      let file = e.target.files[0];
      let target = cover;
      if (file) {
        const reader = new FileReader();
        reader.onloadend = (e) => {
          let attachment = e.target.result;
          console.log(attachment);
          if (attachment) {
            target.attr('src', attachment);
          }
        }
        reader.readAsDataURL(file);
      } else {
        target.attr('src', '');
      }
    });
  }
  addCover($('#coupon_image'), $('#coverImg'));

  //할인구분
  $('#ct2 input').prop('disabled', true);

  $('#coupon_type').change(function(){
    let value = $(this).val();
    $('#ct1 input, #ct2 input').prop('disabled', true);
    if(value == 'fixed'){
      $('#ct1 input').prop('disabled', false);
    } else{
      $('#ct2 input').prop('disabled', false);
    }
  });

  //사용기한
  $('#coupon_type_usage').change(function() {
    let value = $(this).val();
    if (value == 1) {
      $('#startdate, #enddate').prop('disabled', true).val('');
    } else {
      $('#startdate, #enddate').prop('disabled', false);
    }
  });

  if ($('#coupon_type_usage').val() == 1) {
    $('#startdate, #enddate').prop('disabled', true);
  }

// 활성화 체크박스 
  document.addEventListener('DOMContentLoaded', ()=>{
  const activateCheckbox = document.getElementById('coupon_activate');
  const deactivateCheckbox = document.getElementById('coupon_deactivate');

  activateCheckbox.addEventListener('change', ()=>{
    if (activateCheckbox.checked) {
      deactivateCheckbox.checked = false; 
    }
  });

  deactivateCheckbox.addEventListener('change', ()=>{
    if (deactivateCheckbox.checked) {
      activateCheckbox.checked = false;
    }
  });
});

$('.cancel').click(function(e) {
    e.preventDefault();
    if (confirm('정말 취소할까요?')) {
        window.location.href = $(this).attr('href');
    }
});


  function attachFile(file){

  let formData = new FormData(); //페이지전환 없이, 폼전송없이(submit 이벤트 없이) 파일 전송, 빈폼을 생성
  formData.append('savefile',file); //<input type="file" name="savefile" value="file"> 이미지 첨부


  $.ajax({
    url:'coupon_add_image.php',
    data:formData,
    cache: false, //이미지 정보를 브라우저 저장, 안한다
    contentType:false, //전송되는 데이터 타입지정, 안한다.
    processData:false, //전송되는 데이터 처리(해석), 안한다.
    dataType:'json', //product_image_save.php이 반환하는 값의 타입
    type:'POST', //파일 정보를 전달하는 방법
    success:function(returned_data){ //coupon_add_image.php과 연결(성공)되면 할일
      console.log(returned_data);

      if(returned_data.result === 'size'){
        alert('10MB 이하만 첨부할 수 있습니다.');
        return;
      } else if(returned_data.result === 'image'){
        alert('이미지만 첨부할 수 있습니다.');
        return;   
      } else if(returned_data.result === 'error'){
        alert('첨부실패, 관리자에게 문의하세요');
        return;
      } else{ //파일 첨부가 성공하면
        let imgids = $('#coupon_imageId').val() + returned_data.imgid + ',';
        $('#coupon_imageId').val(imgids);
        let html = `
          <div class="card" style="width: 9rem;" id="${returned_data.imgid}">
            <img src="${returned_data.savefile}" class="card-img-top" alt="...">
            <div class="card-body">                
              <button type="button" class="btn btn-danger btn-sm">삭제</button>
            </div>
          </div>
        `;
        $('.coupon_regisImg').append(html);
      }
    }

  })
  } //Attachfile
  //$('#addedImages button');
  //변수.addEventListener('이벤트종류','대상',function(){})

  $('#addedImages').on('click','button', function(){
  let imgid = $(this).closest('.card').attr('id');
  //console.log(imgid);
  file_delete(imgid);
  });


</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/footer.php');
?> 
