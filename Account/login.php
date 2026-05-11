<?php

session_start();
require_once __DIR__ . "/database.php";

$db = new Database();
$conn = $db->conn;

$error = "";
// Xử lý đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'] ;

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
       
       if(hash("sha256", $password) == $user["password"]) {
       
      $_SESSION["id"] = $user["id"];
     
      $stmt->close();
      $conn->close();

      header("Location:book.php");
      exit();
    } else {
      $error= "Sai mật khẩu";
     
    } } else
    {
      $error=  "Không tìm thấy tài khoản";
       
    }


     
}
if (isset($_SESSION["id"])) {
    header("Location: book.php");
    exit();
}



?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login.css">
  <title>Document</title>
  <style>
    /* Thiết kế trang web */
    body {
  font-family: Arial, sans-serif;
  background-image: url("west.jpg");
background-repeat: no-repeat;   /* không lặp lại */
  background-position: center;    /* căn giữa ảnh */
  background-size: cover;         /* ảnh phủ kín toàn bộ màn hình */
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
}

.login-container {
  background-color: #fff;
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  width: 320px;
  text-align: center;
}

.login-container h2 {
  margin-bottom: 20px;
  color: #333;
  font-size: 24px;
}

label {
  display: block;
  margin-top: 10px;
  font-weight: bold;
  color: #555;
  text-align: left;
}

input[type="text"],
input[type="password"] {
  width: 100%;
  padding: 8px;
  margin-top: 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

button {
  width: 100%;
  padding: 10px;
  margin-top: 15px;
  background-color: #4CAF50;
  border: none;
  border-radius: 4px;
  color: white;
  font-size: 16px;
  cursor: pointer;
}

button:hover {
  background-color: #45a049;
}

.error {
  color: red;
  font-size: 14px;
  margin-top: 10px;
}

.forgot-btn {
  background-color: #2196F3;
}

.forgot-btn:hover {
  background-color: #1976D2;
}


  </style>
</head>
<body>
  
  <div class="login-container">
    <h2>Đăng nhập</h2>
    <form method="post" action="login.php" autocomplete="off">
      <label for="email">Email</label>
      <input type="text" name="email" id="email" autocomplete="off" /><br />
      <label for="password">Mật khẩu</label>
      <input type="password" name="password" id="password" autocomplete="off"/><br />
        <?php if (!empty($error)): ?>
    <p style="color:red;"><?php echo $error; ?></p>
  <?php endif; ?>
      <button type="submit">Đăng nhập</button>
      
       
    </form>
     
    <form action="forgot_password.php" method="get">
      <button type="submit" class="forgot-btn">Quên mật khẩu</button>
    </form>
  </div>


</body>
</html>



