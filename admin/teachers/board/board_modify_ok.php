<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/qc/admin/inc/dbcon.php');
$category = $_POST['category'];
$pid = $_POST['pid'];
$title1 = $_POST['title'];  // 제목
$content = $_POST['content'];  // 내용
$img = $_POST['old_img'];  // 기존 이미지값 (새 이미지가 없으면 기존값 사용)
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;



//파일 업로드
$file_name = time() . '_' . $_FILES['file']['name']; //이미지 중복 방지
$temp_path = $_FILES['file']['tmp_name'];
$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/qc/admin/board/upload/' . $file_name; //절대경로로 관리자 upload 폴더로 저장

if (move_uploaded_file($temp_path, $upload_path)) {
    //이미지 출력을 위해 데이터베이스 값 넣기 경로
    $img = '/qc/admin/board/upload/' . $file_name;
}


switch ($category) {
    case 'all':
        $redirect_url = "/qc/admin/teachers/teachers_board/t_read.php?pid=".$pid."&category=all";
        break;
    case 'qna':
        $redirect_url = '/qc/admin/teachers/teachers_board/t_read.php?pid='.$pid.'&category=qna'; 
        break;
    case 'notice':
        $redirect_url = '/qc/admin/teachers/teachers_board/t_read.php?pid='.$pid.'&category=notice'; 
        break;
    case 'event':
        $redirect_url = '/qc/admin/teachers/teachers_board/t_read.php?pid='.$pid.'&category=event'; 
        break;
    case 'free':
        $redirect_url = '/qc/admin/teachers/teachers_board/t_read.php?pid='.$pid.'&category=free'; 
        break;
    default:
        die("유효하지 않은 카테고리입니다.");
}

$sql = "UPDATE board SET title='$title1',content='$content', img='$img', start_date='$start_date', end_date = '$end_date' WHERE pid= $pid";
$result = $mysqli->query($sql);
if($result){
  echo "<script>
    alert('글 수정 완료');
    location.href='$redirect_url';
    </script>";
}else{
  echo "<script>
  alert('글 수정 실패');
   history.back();
  </script>";
}
?>