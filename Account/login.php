<?php

session_start();

$error ="";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password']?? ""; 


if ($email === "sonsacsao@gmail.com" && $password === "123456") {
    $_SESSION['email'] = $email;
    header("Location: book.php");
    exit();
} else {
   $error = "Đăng nhập thất bại";
}


}

?>

<p>Đăng nhập</p>
<form method="post" action="login.php" autocomplete="off">
  <label for="email">Email</label>
  <input type="text" name="email" id="email" autocomplete="off" value=" " /><br />
  <label for="password">Mật khẩu</label>
  <input type="password" name="password" id="password" autocomplete="off" value=" "/><br />
  <button type="submit">Đăng nhập</button>
  
 <?php if (!empty($error)): ?>
  <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>
  
</form>

<form action="forgot_password.php" method="post">
  <button type="submit">Quên mật khẩu</button>
  </form>



