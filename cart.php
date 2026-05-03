<?php
session_start();
$current = basename($_SERVER['PHP_SELF']);
include "connect.php";

// 🔹 Create cart if not exists
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// 🔹 ADD TO CART
if(isset($_POST['add_to_cart'])){
    $menu_id = $_POST['menu_id'];

    if(isset($_SESSION['cart'][$menu_id])){
        $_SESSION['cart'][$menu_id]++;
    } else {
        $_SESSION['cart'][$menu_id] = 1;
    }

    $message = "Item added to cart!";
}

// 🔹 REMOVE ITEM
if(isset($_GET['remove'])){
    $remove_id = $_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
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

<!-- MAIN -->
<div class="container">

<div class="page-header">
    <div>
        <h1>Your Cart</h1>
        <p class="subtitle">Review your items before checkout</p>
    </div>
    <a href="menu.php" class="btn btn--secondary">Back to Menu</a>
</div>

<?php if(isset($message)){ ?>
    <div class="alert alert--success"><?php echo $message; ?></div>
<?php } ?>

<?php if(empty($_SESSION['cart'])){ ?>

    <div class="card text-center">
        <p>Your cart is empty.</p>
        <a href="menu.php" class="btn mt-3">Browse Menu</a>
    </div>

<?php } else { ?>

<?php
$total = 0;

foreach($_SESSION['cart'] as $menu_id => $qty){

    $result = mysqli_query($conn, "SELECT * FROM menu WHERE id='$menu_id'");
    $item = mysqli_fetch_assoc($result);

    $name = $item['name'];
    $price = $item['price'];

    $subtotal = $price * $qty;
    $total += $subtotal;
?>

<div class="cart-item">
    <div>
        <div class="name"><?php echo $name; ?></div>
        <div class="meta">Rs. <?php echo $price; ?></div>
    </div>

    <div>Qty: <?php echo $qty; ?></div>

    <div class="subtotal">Rs. <?php echo $subtotal; ?></div>

    <a href="cart.php?remove=<?php echo $menu_id; ?>" class="btn btn--danger btn--sm">Remove</a>
</div>

<?php } ?>

<div class="cart-total">
    <span class="label">Total</span>
    <span class="amount">Rs. <?php echo $total; ?></span>
</div>

<div class="mt-3 flex">
    <a href="menu.php" class="btn btn--secondary">Continue Shopping</a>
    <a href="place_order.php" class="btn">Checkout</a>
</div>

<?php } ?>

</div>
</div>

</body>
</html>