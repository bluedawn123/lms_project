<?php
$title = '쿠폰 수정';
$coupon_css = "<link href=\"http://{$_SERVER['HTTP_HOST']}/qc/admin/css/coupon.css\" rel=\"stylesheet\" >";
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/header.php');

$cid = $_GET['cid'];
$sql = "SELECT * FROM coupons WHERE cid = $cid";
$result = $mysqli->query($sql);
$data = $result->fetch_object();
?>

<div class="coupon_edit container">
  <form action="coupon_edit_ok.php" id="coupon_submit" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="cid" value="<?= $cid; ?>"> 
  <div class="row coupon">
    <div class="col-4 mb-5">
      <h6>쿠폰 이미지 등록</h6>
          <div class="coupon_regisImg d-flex justify-content-center align-items-center mb-3">
            <img src="<?=$data->coupon_image;?>" id="coverImg" alt="">
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
                <input type=" text" class="form-control" name="coupon_name" id="coupon_name" value="<?= $data->coupon_name; ?>" required>
              </td>
            </tr>
            <tr scope="row">
              <th scope="row" class="insert_name">쿠폰 설명</th>
              <td colspan="3">
              <textarea class="form-control" name="coupon_content" id="coupon_content" rows="2"><?= $data->coupon_content; ?></textarea>
            </tr>
            <tr>
          <th scope="row">할인구분</th>
          <td>
            <select class="form-select" name="coupon_type" id="coupon_type" aria-label="할인구분">                            
              <option value="fixed" <?= $data->coupon_type === 'fixed' ? 'selected' : ''; ?>>정액</option>
              <option value="percentage" <?php if($data->coupon_type === 'percentage'){echo 'selected';}?>>정률</option>
            </select>
          </td>
          <td id="ct1">
            <div class="input-group">
              <input type="text" name="coupon_price" class="form-control" aria-label="할인가" value="<?= $data->coupon_price; ?>" <?= $data->coupon_type === 'fixed' ? '' : 'disabled'; ?>>
              <span class="input-group-text" id="coupon_price">원</span>
            </div>
          </td> 
          <td id="ct2">
            <div class="input-group">
              <input type="text" name="coupon_ratio" class="form-control" aria-label="할인비율" value="<?= $data->coupon_ratio; ?>" <?= $data->coupon_type === 'percentage' ? '' : 'disabled'; ?>>
              <span class="input-group-text" id="coupon_ratio">%</span>
            </div>
          </td>
        </tr>
        </tr> 
        <tr>
          <th scope="row">사용기한</th>
          <td colspan="3">
            <div class="d-flex gap-2">
              <input type="date" class="form-control flex-fill" name="startdate" id="startdate" value="<?= $data->startdate ?? ''; ?>" <?= $data->status == 1 ? 'disabled' : ''; ?> required>
              <input type="date" class="form-control flex-fill" name="enddate" id="enddate" value="<?= $data->enddate ?? ''; ?>" <?= $data->status == 1 ? 'disabled' : ''; ?> required>
            </div>
          </td>
        </tr>
            <tr scope="row">
              <th scope="row" class="insert_name">활성화</th>
              <td colspan="3">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="coupon_activate" id="coupon_activate" value="1" <?= $data->status == 1 ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="coupon_activate">활성화</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="coupon_deactivate" id="coupon_deactivate" value="0" <?= $data->status == 0 ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="coupon_deactivate">비활성화</label>
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
            };
            reader.readAsDataURL(file);
        } else {
            target.attr('src', ''); // 기존 이미지로 되돌리기
        }
    });
}

let coupon_image = $('#coupon_image');
coupon_image.on('change',(e)=>{
    let file = e.target.files;

    const reader = new FileReader(); 
    reader.onloadend = (e)=>{ 
      let attachment = e.target.result;
      if(attachment){
        let target = $('#coverImg');
        target.attr('src',attachment)
      }
    }
    reader.readAsDataURL(file); 
  });



addCover($('#coupon_image'), $('#coverImg'));

  //할인구분
  document.addEventListener('DOMContentLoaded', () => {
    // 할인 구분에 따라 필드 활성화/비활성화
    const updateDiscountFields = () => {
        const type = $('#coupon_type').val();
        $('#ct1 input').prop('disabled', type !== 'fixed');
        $('#ct2 input').prop('disabled', type !== 'percentage');
    };

    // 활성화/비활성화 체크박스 토글
    const toggleActivation = () => {
        $('#coupon_activate').prop('checked', !$('#coupon_deactivate').is(':checked'));
        $('#coupon_deactivate').prop('checked', !$('#coupon_activate').is(':checked'));
    };

    // 사용기한 설정에 따라 날짜 입력 필드 제어
    const updateUsageFields = () => {
        const isUnlimited = $('#coupon_type_usage').val() === '1';
        $('#startdate, #enddate').prop('disabled', isUnlimited).val(isUnlimited ? '' : $('#startdate').val());
    };

    // 초기 설정 및 이벤트 핸들러 등록
    updateDiscountFields();
    toggleActivation();
    updateUsageFields();

    $('#coupon_type').change(updateDiscountFields);
    $('#coupon_activate, #coupon_deactivate').change(toggleActivation);
    $('#coupon_type_usage').change(updateUsageFields);
});

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

   // 사용기한 필드 활성화/비활성화 설정
 const updateUsageFields = () => {
        const isUnlimited = $('#coupon_type_usage').val() === '1';
        $('#startdate, #enddate').prop('disabled', isUnlimited);
    };

    // 초기 설정
    updateUsageFields();

    // 사용기한 변경 시 업데이트
    $('#coupon_type_usage').change(updateUsageFields);
});

  $('.cancel').click(function(e) {
      e.preventDefault();
      if (confirm('정말 취소할까요?')) {
          window.location.href = $(this).attr('href');
      }
  });


</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/footer.php');
?> 
