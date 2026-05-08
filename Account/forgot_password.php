<?php
session_start();
if (empty($_SESSION['allow_forgot'])) {
    header("Location: login.php");
    exit();
}
 unset($_SESSION['allow_forgot']);

require_once __DIR__ . '/database.php';
$db = new Database();
$conn = $db->conn;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['email']) || empty(trim($_POST['email']))) {
        $error = "Vui lòng nhập lại email";
    } 
    elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ";
    } 
    else {
        $email = $_POST['email'];
        $token = bin2hex(random_bytes(16));

        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $error = "Email không tồn tại";
        } else {
            $stmt = $conn->prepare("UPDATE users SET reset_token=? WHERE email=?");
            $stmt->bind_param("ss", $token, $email);
            $stmt->execute();
            $stmt->close();
            $conn->close();

         

            header("Location: reset_password.php?token=" . $token);
            exit();
        }
    }
}
?>

<!-- HTML phần trước -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Toàn bộ trang */
body {
  font-family: Arial, sans-serif;
  background: #f4f6f9;
  margin: 0;
  padding: 20px;
}

/* Form */
form {
  max-width: 400px;
  margin: 50px auto;
  background: #fff;
  padding: 25px;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Label */
form label {
  display: block;
  margin-bottom: 8px;
  font-weight: bold;
  color: #333;
}

/* Input */
form input[type="email"] {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

/* Nút submit */
form button {
  width: 100%;
  padding: 12px;
  background: #4CAF50;
  border: none;
  border-radius: 4px;
  color: #fff;
  font-size: 16px;
  cursor: pointer;
}

form button:hover {
  background: #45a049;
}

    </style>
</head>
<body>
  
<form method="post" action="forgot_password.php">
    <label for="email">Nhập email của bạn:</label>
    <input type="email" name="email" id="email" required>
     <?php if (!empty($error)): ?>
    <p style="color:red;"><?php echo $error; ?></p>
  <?php endif; ?>
    <button type="submit">Gửi yêu cầu</button>
</form>
</body>
</html>
