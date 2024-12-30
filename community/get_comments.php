<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

header('Content-Type: application/json');

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if ($post_id > 0) {
    $sql = "SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }

    echo json_encode(['status' => 'success', 'comments' => $comments]);
} else {
    echo json_encode(['status' => 'error', 'message' => '잘못된 게시물 ID입니다.']);
}

$mysqli->close();
?>