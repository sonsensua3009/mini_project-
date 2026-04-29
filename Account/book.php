<?php
require_once __DIR__ . '/database.php';
$db = new Database();
$conn = $db->conn ;

// Thêm sách
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $conn->query("INSERT INTO books (title, author, price) VALUES ('$title','$author','$price')");
}

// Sửa sách
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $conn->query("UPDATE books SET title='$title', author='$author', price='$price' WHERE id=$id");
}

// Xóa sách
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM books WHERE id=$id");
}

// Lấy danh sách sách
$result = $conn->query("SELECT * FROM books");
?>

<h2>Thêm sách</h2>
<form method="post">
    <input type="text" name="title" placeholder="Tên sách" required>
    <input type="text" name="author" placeholder="Tác giả">
    <input type="number" name="price" placeholder="Năm xuất bản">
    <button type="submit" name="add">Thêm</button>
</form>

<h2>Danh sách</h2>
<table border="1" cellpadding="5">
    <tr><th>ID</th><th>Tên sách</th><th>Tác giả</th><th>Giá tiền</th></tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <form method="post">
            <td><?php echo $row['id']; ?><input type="hidden" name="id" value="<?php echo $row['id']; ?>"></td>
            <td><input type="text" name="title" value="<?php echo $row['title']; ?>"></td>
            <td><input type="text" name="author" value="<?php echo $row['author']; ?>"></td>
            <td><input type="number" name="price" value="<?php echo $row['price']; ?>"></td>
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
