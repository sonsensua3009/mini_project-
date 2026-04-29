<?php

session_start();


$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password']; 


if ($email === "sonsacsao@gmail.com" && $password === "123456") {
    $_SESSION['email'] = $email;
    header("Location: book.php");
    exit();
} else {
   
    header("Location: login.html");
    exit();
}
?>



