<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/qc/admin/inc/dbcon.php');

// 세션에서 사용자 ID 가져오기
if (!isset($_SESSION['MemId'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href = '/qc/index.php';</script>";
    exit;
}

$memId = $_SESSION['MemId'];

// 쪽지 목록 가져오기
$sql = "SELECT * FROM tomembermessages WHERE receiver_id = ? ORDER BY sent_at DESC";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $memId);
$stmt->execute();
$result = $stmt->get_result();

// 쪽지 데이터 배열에 저장
$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>쪽지함</title>
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <style>
        .container {
            margin-top: 20px;
        }
        .message-card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }
        .message-card-header {
            background-color: #f8f9fa;
            padding: 15px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .message-card-body {
            padding: 15px;
        }
        .message-card-footer {
            padding: 10px;
            background-color: #f8f9fa;
            text-align: right;
        }
        .no-messages {
            text-align: center;
            margin: 50px 0;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="mb-4">쪽지함</h3>
        <?php if (empty($messages)): ?>
            <p class="no-messages"><i class="fas fa-envelope-open-text fa-2x"></i><br>아직 새로운 쪽지가 없습니다.</p>
        <?php else: ?>
            <?php foreach ($messages as $message): ?>
                <div class="message-card">
                    <div class="message-card-header">
                        <span><?= htmlspecialchars($message['sender_name']); ?></span>
                        <small class="text-muted"><?= date("Y-m-d H:i", strtotime($message['sent_at'])); ?></small>
                    </div>
                    <div class="message-card-body">
                        <p><?= nl2br(htmlspecialchars($message['message_content'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- 답장 모달 -->
    <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="replyModalLabel">답장 보내기</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="replyForm">
                        <input type="hidden" id="receiverId" name="receiverId">
                        <div class="mb-3">
                            <label for="receiverName" class="form-label">받는 사람</label>
                            <input type="text" id="receiverName" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="messageContent" class="form-label">쪽지 내용</label>
                            <textarea id="messageContent" name="messageContent" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">보내기</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 모달에 데이터 전달
        document.getElementById('replyModal').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const senderId = button.getAttribute('data-sender-id');
            const senderName = button.getAttribute('data-sender-name');

            document.getElementById('receiverId').value = senderId;
            document.getElementById('receiverName').value = senderName;
        });

        // 쪽지 보내기 폼 처리
        document.getElementById('replyForm').addEventListener('submit', function (event) {
            event.preventDefault();

            const receiverId = document.getElementById('receiverId').value;
            const messageContent = document.getElementById('messageContent').value;

            fetch('/qc/send_message.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    receiverId: receiverId,
                    messageContent: messageContent
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('쪽지가 성공적으로 전송되었습니다.');
                    document.getElementById('replyModal').querySelector('.btn-close').click();
                } else {
                    alert('쪽지 전송에 실패했습니다. 다시 시도해주세요.');
                }
            })
            .catch(error => {
                console.error('쪽지 전송 중 오류:', error);
                alert('오류가 발생했습니다.');
            });
        });
    </script>
</body>
</html>
