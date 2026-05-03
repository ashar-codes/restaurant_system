<?php
session_start();
include "connect.php";

// 🔒 Only admin allowed
if(!isset($_SESSION['user_id']) || $_SESSION['active_role'] != 'admin'){
    echo "Access Denied!";
    exit();
}

// 🔹 DELETE
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM menu WHERE id='$id'");
}

// 🔹 UPDATE
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    mysqli_query($conn, "UPDATE menu 
                         SET name='$name', price='$price' 
                         WHERE id='$id'");
}

// 🔹 FETCH MENU
$result = mysqli_query($conn, "SELECT * FROM menu");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Menu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app">

<!-- NAVBAR -->
<nav class="navbar">
    <div class="brand">Saffron<span class="dot">.</span></div>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="admin_manage_menu.php">Manage Menu</a>
        <span class="badge badge--role">admin</span>
        <a href="logout.php" class="btn btn--ghost btn--sm">Logout</a>
    </div>
</nav>

<div class="container">

<div class="page-header">
    <div>
        <h1>Manage Menu</h1>
        <p class="subtitle">Edit or remove food items</p>
    </div>
    <a href="admin_menu.php" class="btn">➕ Add New Item</a>
</div>

<?php if(mysqli_num_rows($result) > 0){ ?>

<div class="card">

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<form method="POST" class="list-item flex space-between align-center">

<div>
    <strong>#<?php echo $row['id']; ?></strong>
</div>

<div>
    <input type="text" name="name" value="<?php echo $row['name']; ?>">
</div>

<div>
    <input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>">
</div>

<div class="flex gap-2">

    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <input type="submit" name="update" value="Update" class="btn btn--secondary btn--sm">

    <a href="admin_manage_menu.php?delete=<?php echo $row['id']; ?>" 
       class="btn btn--danger btn--sm">
       Delete
    </a>

</div>

</form>

<?php } ?>

</div>

<?php } else { ?>

<div class="card text-center">
    No menu items found.
</div>

<?php } ?>

<br>
<a href="dashboard.php" class="btn btn--ghost">← Back to Dashboard</a>

</div>
</div>

</body>
</html>