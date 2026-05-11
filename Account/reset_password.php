<?php
// Xử lý logic tạo mật khẩu mới

session_start();



require_once __DIR__ ."/database.php";
$db = new Database();
$conn = $db->conn;
$error = "";
$token = $_GET["token"]?? "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$password = $_POST["new_password"]??"";
$confirm = $_POST["confirm_password"]??"";
$token = $_POST["token"]??"";

if (!$password||!$confirm) {
     $error = "Vui lòng nhập mật khẩu";
}

elseif ($password !== $confirm) {
    $error = "Mật khẩu không khớp";
}
else{
 
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Token không hợp lệ");
    }
    $user = $result->fetch_assoc();
    $hashed = hash("sha256", $password);
    $stmt = $conn->prepare("UPDATE users SET password=?, reset_token = NULL WHERE id=?");
    $stmt->bind_param("si", $hashed, $user['id']);
    $stmt->execute();

    echo "Đổi mật khẩu thành công!";
   
    exit;
}
}
 unset($_SESSION["allow_forgot"]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
  <link rel="stylesheet" type="text/css" href="reset_password.css">
  <style>
    /* reset_password.css */

body {
  font-family: Arial, sans-serif;
  background-color: #f4f6f8;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

form {
  background: #fff;
  padding: 20px 30px;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  width: 300px;
}

form label {
  display: block;
  margin-bottom: 6px;
  font-weight: bold;
  color: #333;
}

form input[type="password"] {
  width: 100%;
  padding: 8px 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

form button {
  width: 100%;
  padding: 10px;
  background-color: #4CAF50;
  border: none;
  border-radius: 4px;
  color: white;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s;
}

form button:hover {
  background-color: #45a049;
}

  </style>
</head>
<body>
<form method="post" action="reset_password.php">
 <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
     <label for="new_password">Mật khẩu mới:</label>
    <input type="password" name="new_password" id="new_password" required><br>
    <label for="confirm_password">Xác nhận mật khẩu:</label>
    <input type="password" name="confirm_password" id="confirm_password" required><br>

    <button type="submit">Đổi mật khẩu</button>
</form>
</body>
</html>