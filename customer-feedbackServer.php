<?php
// 1. 檢查是否有資料送來
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. 取得表單資料，並基本過濾
    $name = htmlspecialchars(trim($_POST["name"] ?? ''));
    $email = htmlspecialchars(trim($_POST["email"] ?? ''));
    $rating = htmlspecialchars(trim($_POST["rating"] ?? ''));
    $feedback = htmlspecialchars(trim($_POST["feedback"] ?? ''));

    // 3. 基本驗證
    if (!$name || !$email || !$rating || !$feedback) {
        echo "請填寫所有欄位。";
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "電子郵件格式錯誤。";
        exit;
    }

    // 4. 儲存資料（這裡以寫入文字檔案為例）
    $feedbackLine = date("Y-m-d H:i:s") . " | $name | $email | $rating | $feedback" . PHP_EOL;
    file_put_contents("feedback.txt", $feedbackLine, FILE_APPEND);

    // 5. 可改存DB或寄信，這裡先回饋成功訊息
    echo "感謝您的回饋！";
} else {
    echo "非法存取。";
}
?>
