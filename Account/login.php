<?php

session_start();


$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password']?? ""; 


if ($email === "sonsacsao@gmail.com" && $password === "123456") {
    $_SESSION['email'] = $email;
    header("Location: book.php");
    exit();
} else {
   
    $error = "Đăng nhập thất bại";
}



?>

<p>Đăng nhập</p>
<form method="post" action="login.php">
  <label for="email">Email</label>
  <input type="text" name="email" id="email" required /><br />
  <label for="password">Mật khẩu:</label>
  <input type="password" name="password" id="password" required /><br />
  <button type="submit">Đăng nhập</button>
  <span><button type="submit">Quên mật khẩu</button></span>
 <?php if (!empty($error)): ?>
  <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>
  
</form>



