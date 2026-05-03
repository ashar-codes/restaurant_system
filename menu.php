<?php
session_start();
$current = basename($_SERVER['PHP_SELF']);
include "connect.php";

// 🔒 Optional: check login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Get all menu items
$result = mysqli_query($conn, "SELECT * FROM menu");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Menu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app">

<!-- NAVBAR -->
<nav class="navbar">
    <div class="brand">Saffron<span class="dot">.</span></div>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="menu.php">Menu</a>
        <a href="cart.php">Cart</a>
        <span class="badge badge--role"><?php echo $_SESSION['active_role']; ?></span>
        <a href="logout.php" class="btn btn--ghost btn--sm">Logout</a>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container container--wide">

<div class="page-header">
    <div>
        <h1>Restaurant Menu</h1>
        <p class="subtitle">Fresh dishes, prepared to order.</p>
    </div>
    <a href="cart.php" class="btn btn--secondary">View Cart</a>
</div>

<div class="card-grid">

<?php
// Check if menu has items
if(mysqli_num_rows($result) > 0){

    // Loop through each item
    while($row = mysqli_fetch_assoc($result)){
?>

    <div class="menu-item">

        <?php if(!empty($row['image'])){ ?>
            <img src="<?php echo $row['image']; ?>">
        <?php } ?>

        <div class="menu-body">
            <h3><?php echo $row['name']; ?></h3>
            <div class="price">Rs. <?php echo $row['price']; ?></div>

            <form method="POST" action="cart.php">
                <input type="hidden" name="menu_id" value="<?php echo $row['id']; ?>">
                <input type="submit" name="add_to_cart" value="Add to Cart" class="btn btn--block">
            </form>
        </div>

    </div>

<?php
    }

} else {
    echo "<div class='card text-center'>No menu items available.</div>";
}
?>

</div>

<br>
<a href="dashboard.php" class="btn btn--ghost">← Back to Dashboard</a>

</div>

</div>

</body>
</html>