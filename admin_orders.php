<?php
session_start();
include "connect.php";

// 🔒 Only admin
if(!isset($_SESSION['user_id']) || $_SESSION['active_role'] != 'admin'){
    echo "Access Denied!";
    exit();
}

// 🔹 UPDATE STATUS
if(isset($_POST['update_status'])){
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    mysqli_query($conn, "UPDATE orders SET status='$status' WHERE id='$order_id'");
}

// 🔹 ASSIGN RIDER
if(isset($_POST['assign_rider'])){
    $order_id = $_POST['order_id'];
    $rider_id = $_POST['rider_id'];

    mysqli_query($conn, "UPDATE orders 
                         SET rider_id='$rider_id', status='Assigned' 
                         WHERE id='$order_id'");
}

// 🔹 GET ORDERS
$orders = mysqli_query($conn, "SELECT * FROM orders");

// 🔹 GET RIDERS (for dropdown)
$riders = mysqli_query($conn, "
SELECT users.id, users.name 
FROM users
JOIN user_roles ON users.id = user_roles.user_id
JOIN roles ON roles.id = user_roles.role_id
WHERE roles.role_name='rider'
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Orders</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app">

<!-- NAVBAR -->
<nav class="navbar">
    <div class="brand">Saffron<span class="dot">.</span></div>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="admin_manage_menu.php">Menu</a>
        <span class="badge badge--role">admin</span>
        <a href="logout.php" class="btn btn--ghost btn--sm">Logout</a>
    </div>
</nav>

<div class="container">

<div class="page-header">
    <h1>Orders</h1>
    <p class="subtitle">Manage and track all orders</p>
</div>

<?php while($order = mysqli_fetch_assoc($orders)){ ?>

<div class="card order-card">

<div class="flex space-between">

<div>
    <h3>Order #<?php echo $order['id']; ?></h3>
    <p class="meta">Status: <strong><?php echo $order['status']; ?></strong></p>
    <p class="meta">Type: <?php echo $order['order_type']; ?></p>

    <?php if($order['order_type'] == 'delivery'){ ?>
        <p class="meta">📍 <?php echo $order['address']; ?></p>
    <?php } ?>

    <?php if($order['order_type'] == 'dine-in'){ ?>
        <p class="meta">🍽 Table <?php echo $order['table_no']; ?></p>
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
WHERE order_items.order_id = '".$order['id']."'
");

while($item = mysqli_fetch_assoc($items)){
    echo "<div class='list-item'>";
    echo $item['name'] . " × " . $item['quantity'];
    echo "</div>";
}
?>

<hr>

<div class="flex gap-3">

<!-- 🔹 UPDATE STATUS -->
<form method="POST" class="flex gap-2 align-center">
    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">

    <select name="status">
        <option>Pending</option>
        <option>Preparing</option>
        <option>Delivered</option>
    </select>

    <input type="submit" name="update_status" value="Update" class="btn btn--secondary btn--sm">
</form>

<!-- 🔹 ASSIGN RIDER -->
<form method="POST" class="flex gap-2 align-center">

    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">

    <select name="rider_id" required>
        <option value="">Select Rider</option>

        <?php 
        mysqli_data_seek($riders, 0);
        while($r = mysqli_fetch_assoc($riders)){ ?>
            <option value="<?php echo $r['id']; ?>">
                <?php echo $r['name']; ?> (ID: <?php echo $r['id']; ?>)
            </option>
        <?php } ?>

    </select>

    <input type="submit" name="assign_rider" value="Assign" class="btn btn--primary btn--sm">

</form>

</div>

</div>

<?php } ?>

<br>
<a href="dashboard.php" class="btn btn--ghost">← Back</a>

</div>
</div>

</body>
</html>