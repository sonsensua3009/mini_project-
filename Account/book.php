<?php

session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit() ;
}

require_once __DIR__ . '/database.php';
$db = new Database();
$conn = $db->conn;


if (isset($_POST['add'])) {
    $title  = $_POST['title'];
    $author = $_POST['author'];
    $price  = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO books (title, author, price) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $title, $author, $price);
    $stmt->execute();
}

// Sửa sách
if (isset($_POST['update'])) {
    $id     = $_POST['id'];
    $title  = $_POST['title'];
    $author = $_POST['author'];
    $price  = $_POST['price'];

    $stmt = $conn->prepare("UPDATE books SET title=?, author=?, price=? WHERE id=?");
    $stmt->bind_param("ssdi", $title, $author, $price, $id);
    $stmt->execute();
}

// Xóa sách
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM books WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Lấy danh sách sách
$result = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Nền và font */
body {
  font-family: Arial, sans-serif;
  background: #f4f6f9;
  margin: 0;
  padding: 20px;
}

/* Tiêu đề */
h2 {
  color: #333;
  text-align: center;
  margin-bottom: 15px;
}

/* Form thêm sách */
form {
  margin: 0 auto 20px auto;
  max-width: 400px;
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

form input[type="text"],
form input[type="number"] {
  width: 100%;
  padding: 8px;
  margin: 8px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
}

form button {
  width: 100%;
  padding: 10px;
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

/* Bảng danh sách */
table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  margin-top: 20px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

table th, table td {
  padding: 10px;
  text-align: center;
  border: 1px solid #ddd;
}

table th {
  background: #4CAF50;
  color: #fff;
}

table tr:nth-child(even) {
  background: #f9f9f9;
}

table input[type="text"],
table input[type="number"] {
  width: 90%;
  padding: 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

/* Nút sửa và xóa */
table button {
  background: #2196F3;
  border: none;
  padding: 6px 10px;
  border-radius: 4px;
  color: #fff;
  cursor: pointer;
}

table button:hover {
  background: #1976D2;
}

table a {
  display: inline-block;
  margin-left: 5px;
  padding: 6px 10px;
  background: #f44336;
  color: #fff;
  border-radius: 4px;
  text-decoration: none;
}

table a:hover {
  background: #d32f2f;
}

/* Nút đăng xuất */
form button[name="logout"] {
  background: #ff9800;
}

form button[name="logout"]:hover {
  background: #e68900;
}

    </style>
</head>
<body>
    <h2>Thêm sách</h2>
<form method="post">
    <input type="text" name="title" placeholder="Tên sách" required>
    <input type="text" name="author" placeholder="Tác giả">
    <input type="number" name="price" placeholder="Giá tiền">
    <button type="submit" name="add">Thêm</button>
</form>

<h2>Danh sách</h2>
<table border="1" cellpadding="5">
    <tr><th>ID</th><th>Tên sách</th><th>Tác giả</th><th>Giá tiền</th></tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <form method="post">
            <td><?php echo $row['id']; ?><input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id'],ENT_QUOTES,'UTF-8'); ?>"></td>
            <td><input type="text" name="title" value="<?php echo htmlspecialchars($row['title'],ENT_QUOTES,'UTF-8'); ?>"></td>
            <td><input type="text" name="author" value="<?php echo htmlspecialchars($row['author'],ENT_QUOTES,'UTF-8'); ?>"></td>
            <td><input type="number" name="price" value="<?php echo htmlspecialchars($row['price'],ENT_QUOTES,'UTF-8'); ?>"></td>
            <td>
                <button type="submit" name="update">Sửa</button>
                <a href="book.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Xóa sách này?')">Xóa</a>
            </td>
        </form>
    </tr>
    <?php endwhile; ?>
</table>

<form action="logout.php" method="post">
<button type="submit" name="logout" >Đăng xuất</button>
</form>

</body>
</html>
