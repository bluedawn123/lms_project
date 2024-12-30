<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

// 요청이 POST인지 확인
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
    $user_id = $_SESSION['MemEmail'];

    // 게시물 작성자 확인
    $sql = "SELECT user_id FROM board WHERE pid = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();

    if ($post && $post['user_id'] === $user_id) {
        // recruit_status를 1로 업데이트
        $update_sql = "UPDATE board SET recruit_status = 1 WHERE pid = ?";
        $update_stmt = $mysqli->prepare($update_sql);
        $update_stmt->bind_param("i", $pid);

        if ($update_stmt->execute()) {
            // 성공 메시지를 세션에 저장
            $_SESSION['recruit_status_message'] = '모집완료로 변경하였습니다.';
            header("Location: /qc/community/study.php"); // 기존 페이지로 이동
            exit;
        } else {
            // 오류 발생 시 기존 페이지로 이동하며 오류 메시지 전달
            $_SESSION['recruit_status_message'] = '모집 상태 업데이트 중 오류가 발생했습니다.';
            header("Location: /qc/community/study.php");
            exit;
        }
    } else {
        // 권한이 없는 경우
        $_SESSION['recruit_status_message'] = '권한이 없습니다.';
        header("Location: /qc/community/study.php");
        exit;
    }
} else {
    // 잘못된 요청일 경우
    $_SESSION['recruit_status_message'] = '잘못된 요청입니다.';
    header("Location: /qc/community/study.php");
    exit;
}