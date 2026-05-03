<?php
session_start();
include "connect.php";

// 🔒 Only admin allowed
if(!isset($_SESSION['user_id']) || $_SESSION['active_role'] != 'admin'){
    echo "Access Denied!";
    exit();
}

$message = "";

if(isset($_POST['add'])){

    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    $sql = "INSERT INTO menu (name, price, image)
            VALUES ('$name', '$price', '$image')";

    if(mysqli_query($conn, $sql)){
        $message = "Item added successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Menu Item</title>
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

<div class="container container--narrow">

<div class="auth-card">

<h2>Add Menu Item</h2>

<?php if($message){ ?>
    <div class="alert alert--success"><?php echo $message; ?></div>
<?php } ?>

<form method="POST">

<div class="form-group">
<label>Item Name</label>
<input type="text" name="name" required>
</div>

<div class="form-group">
<label>Price</label>
<input type="number" step="0.01" name="price" required>
</div>

<div class="form-group">
<label>Image Path</label>
<input type="text" name="image" placeholder="images/burger.jpg">
</div>

<input type="submit" name="add" value="Add Item" class="btn btn--block">

</form>

<br>
<a href="admin_manage_menu.php" class="btn btn--ghost">← Back to Menu</a>

</div>

</div>

</div>

</body>
</html>