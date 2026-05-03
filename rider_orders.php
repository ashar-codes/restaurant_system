<?php
session_start();
include "connect.php";

// 🔒 Only rider
if(!isset($_SESSION['user_id']) || $_SESSION['active_role'] != 'rider'){
    echo "Access Denied!";
    exit();
}

$rider_id = $_SESSION['user_id'];

// 🔹 Mark delivered
if(isset($_POST['mark_delivered'])){
    $order_id = $_POST['order_id'];

    mysqli_query($conn, "UPDATE orders 
                         SET status='Delivered' 
                         WHERE id='$order_id' AND rider_id='$rider_id'");
}

// 🔹 Get assigned orders
$orders = mysqli_query($conn, "
    SELECT * FROM orders 
    WHERE rider_id='$rider_id'
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Deliveries</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app">

<!-- NAVBAR -->
<nav class="navbar">
    <div class="brand">Saffron<span class="dot">.</span></div>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <span class="badge badge--role">rider</span>
        <a href="logout.php" class="btn btn--ghost btn--sm">Logout</a>
    </div>
</nav>

<div class="container">

<div class="page-header">
    <h1>My Deliveries</h1>
    <p class="subtitle">Orders assigned to you</p>
</div>

<?php if(mysqli_num_rows($orders) == 0){ ?>

<div class="card text-center">
    No deliveries assigned.
</div>

<?php } ?>

<?php while($order = mysqli_fetch_assoc($orders)){ ?>

<div class="card order-card">

<div class="flex space-between">

<div>
    <h3>Order #<?php echo $order['id']; ?></h3>

    <p class="meta">
        Status:
        <strong class="
        <?php 
            if($order['status'] == 'Delivered') echo 'text-success';
            elseif($order['status'] == 'Preparing') echo 'text-warning';
            else echo 'text-muted';
        ?>">
        <?php echo $order['status']; ?>
        </strong>
    </p>

    <?php if($order['order_type'] == 'delivery'){ ?>
        <p class="meta">📍 <?php echo $order['address']; ?></p>
    <?php } ?>
</div>

</div>

<hr>

<h4>Items</h4>

<?php
$items = mysqli_query($conn, "
SELECT menu.name, order_items.quantity
FROM order_items
JOIN menu ON menu.id = order_items.menu_id
WHERE order_items.order_id='".$order['id']."'
");

while($item = mysqli_fetch_assoc($items)){
    echo "<div class='list-item'>";
    echo $item['name']." × ".$item['quantity'];
    echo "</div>";
}
?>

<hr>

<form method="POST">

<input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">

<?php if($order['status'] != 'Delivered'){ ?>

<input type="submit" name="mark_delivered" value="Mark Delivered" class="btn btn--primary">

<?php } else { ?>

<button class="btn btn--secondary" disabled>Already Delivered</button>

<?php } ?>

</form>

</div>

<?php } ?>

<br>
<a href="dashboard.php" class="btn btn--ghost">← Back</a>

</div>
</div>

</body>
</html>