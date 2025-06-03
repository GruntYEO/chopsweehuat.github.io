<?php
session_start();

// 取得表單資料
$guest_name = isset($_POST['guest-name']) ? trim($_POST['guest-name']) : '';
$guest_email = isset($_POST['email']) ? trim($_POST['email']) : '';

// 檢查表單是否送出且資料齊全
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 檢查名稱與信箱是否有輸入
    if ($guest_name === '' || $guest_email === '') {
        echo "<script>alert('請輸入完整資訊！');history.back();</script>";
        exit();
    }

    // 檢查 Email 格式
    if (!filter_var($guest_email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('電子郵件格式不正確！');history.back();</script>";
        exit();
    }

    // 寫入 Session（可用於辨識訪客狀態）
    $_SESSION['guest_name'] = $guest_name;
    $_SESSION['guest_email'] = $guest_email;

    // 可選：寫入資料庫，如需統計或記錄訪客資料（需先建表 guests）
    $host = "localhost";
    $user = "root";
    $passwordDB = "";
    $dbname = "test";
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    try {
        $Connect = new PDO($dsn, $user, $passwordDB);
        $Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $Connect->prepare("INSERT INTO guests (name, email, login_time) VALUES (:name, :email, NOW())");
        $stmt->execute([':name' => $guest_name, ':email' => $guest_email]);
    } catch (PDOException $e) {
        // 不影響流程
    }

    // 登入成功，導向首頁或其他頁面
    echo "<script>alert('歡迎訪客 {$guest_name}！');window.location.href='index.html';</script>";
    exit();
}
?>
