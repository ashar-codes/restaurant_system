<?php
session_start();
$current = basename($_SERVER['PHP_SELF']);
// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Get active role
$active_role = $_SESSION['active_role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
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
        <span class="badge badge--role"><?php echo $active_role; ?></span>
        <a href="logout.php" class="btn btn--ghost btn--sm">Logout</a>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container">

<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p class="subtitle">Logged in as <strong><?php echo ucfirst($active_role); ?></strong></p>
    </div>
</div>

<div class="tile-grid">

<?php if($active_role == 'customer'){ ?>

    <a href="menu.php" class="tile">
        <h3>Order Food</h3>
        <p>Browse menu and place orders</p>
    </a>

    <a href="cart.php" class="tile">
        <h3>View Cart</h3>
        <p>Check your selected items</p>
    </a>

<?php } ?>

<?php if($active_role == 'waiter'){ ?>

    <a href="waiter_orders.php" class="tile">
        <h3>Take Order</h3>
        <p>Create dine-in orders</p>
    </a>

<?php } ?>

<?php if($active_role == 'rider'){ ?>

    <a href="rider_orders.php" class="tile">
        <h3>My Deliveries</h3>
        <p>View and deliver assigned orders</p>
    </a>

<?php } ?>

<?php if($active_role == 'admin'){ ?>

    <a href="admin_orders.php" class="tile">
        <h3>Manage Orders</h3>
        <p>Update status and assign riders</p>
    </a>

    <a href="admin_manage_menu.php" class="tile">
        <h3>Manage Menu</h3>
        <p>Add, edit, and delete menu items</p>
    </a>

<?php } ?>

<?php if($active_role == 'manager'){ ?>

    <a href="manager_panel.php" class="tile">
        <h3>Manager Panel</h3>
        <p>Create staff and assign roles</p>
    </a>

<?php } ?>

</div>

</div>

</div>

</body>
</html>