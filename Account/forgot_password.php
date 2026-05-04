<?php

//Khởi chạy phiên và kết nối database
session_start();
require_once __DIR__ . '/database.php';
$db = new Database();
$conn = $db->conn;
$email="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$email = filter_input(INPUT_POST,"email", FILTER_SANITIZE_EMAIL);
$token= bin2hex(random_bytes(16));
}

//Kiểm tra xem email có tồn tại hay không 
if (!$email) {
    echo "Vui lòng nhập lại email";
    exit;
}
else{
$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Email không tồn tại";
        exit;
    }


    $stmt = $conn->prepare("UPDATE users SET reset_token=? WHERE email=?");
    $stmt->bind_param("ss", $token, $email);
    $stmt->execute();

    
    header("Location: reset_password.php?token=" . $token);
    exit;
}
 



?>

//Form nhập gmail 
<form method="post" action="forgot_password.php">
    <label for="email">Nhập email của bạn:</label>
    <input type="email" name="email" id="email" required>
    <button type="submit">Gửi yêu cầu</button>

</form>

