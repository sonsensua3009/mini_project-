<?php
require_once __DIR__ ."/database.php";
$db = new Database();
$conn = $db->conn;

$token = $_GET["token"]?? " ";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$password = $_POST["new_password"]??" ";
$confirm = $_POST["confirm_password"]??" ";
$token = $_POST["token"]??" ";

if (!$password||!$confirm) {
     die ("Vui lòng nhập vào!");
}

if ($password !== $confirm) {
    die ("Mật khẩu không khớp");
}

 
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Token không hợp lệ");
    }

    $user = $result->fetch_assoc();


    $hashed = password_hash($password, PASSWORD_DEFAULT);

   
    $stmt = $conn->prepare("UPDATE users SET password=?, reset_token=NULL WHERE id=?");
    $stmt->bind_param("si", $hashed, $user['id']);
    $stmt->execute();

    echo "Đổi mật khẩu thành công!";
    exit;

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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