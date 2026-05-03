<?php
session_start();
include "connect.php";

// 🔒 Only waiter
if(!isset($_SESSION['user_id']) || $_SESSION['active_role'] != 'waiter'){
    echo "Access Denied!";
    exit();
}

$waiter_id = $_SESSION['user_id'];
$message = "";

// 🔹 HANDLE SUBMIT
if(isset($_POST['place_order'])){

    $table_no = $_POST['table_no'];
    $payment = $_POST['payment_method'];

    // guard: no items selected
    if(empty($_POST['items'])){
        $message = "Please select at least one item.";
    } else {

        // 🔹 Create order
        mysqli_query($conn, "INSERT INTO orders 
            (user_id, order_type, table_no, payment_method, status)
            VALUES ('$waiter_id', 'dine-in', '$table_no', '$payment', 'Pending')");

        $order_id = mysqli_insert_id($conn);

        // 🔹 Insert items
        foreach($_POST['items'] as $menu_id){
            $qty = isset($_POST['qty_'.$menu_id]) ? (int)$_POST['qty_'.$menu_id] : 1;
            if($qty < 1) $qty = 1;

            mysqli_query($conn, "INSERT INTO order_items 
                (order_id, menu_id, quantity)
                VALUES ('$order_id','$menu_id','$qty')");
        }

        $message = "Dine-in order created successfully!";
    }
}

// 🔹 FETCH MENU
$menu = mysqli_query($conn, "SELECT * FROM menu");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Take Dine-in Order</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app">

<!-- NAVBAR -->
<nav class="navbar">
    <div class="brand">Saffron<span class="dot">.</span></div>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <span class="badge badge--role">waiter</span>
        <a href="logout.php" class="btn btn--ghost btn--sm">Logout</a>
    </div>
</nav>

<div class="container">

<div class="page-header">
    <h1>Take Dine-in Order</h1>
    <p class="subtitle">Create an order for a table</p>
</div>

<?php if($message){ ?>
    <div class="alert <?php echo (strpos($message, 'successfully') !== false) ? 'alert--success' : 'alert--error'; ?>">
        <?php echo $message; ?>
    </div>
<?php } ?>

<form method="POST">

<!-- ORDER DETAILS -->
<div class="card mb-3">

<div class="form-group">
    <label>Table Number</label>
    <input type="number" name="table_no" required>
</div>

<div class="form-group">
    <label>Payment Method</label>
    <select name="payment_method">
        <option value="cash">Cash</option>
        <option value="card">Card</option>
    </select>
</div>

</div>

<!-- MENU ITEMS -->
<div class="card">

<h3>Select Items</h3>

<div class="card-grid">

<?php while($item = mysqli_fetch_assoc($menu)){ ?>

<label class="menu-item selectable">

    <input type="checkbox" name="items[]" value="<?php echo $item['id']; ?>">

    <?php if(!empty($item['image'])){ ?>
        <img src="<?php echo $item['image']; ?>">
    <?php } ?>

    <div class="menu-body">
        <h4><?php echo $item['name']; ?></h4>
        <div class="price">Rs. <?php echo $item['price']; ?></div>

        <div class="qty-row">
            <span>Qty</span>
            <input type="number" min="1" name="qty_<?php echo $item['id']; ?>" value="1">
        </div>
    </div>

</label>

<?php } ?>

</div>

</div>

<br>

<input type="submit" name="place_order" value="Place Order" class="btn btn--block">

</form>

<br>
<a href="dashboard.php" class="btn btn--ghost">← Back</a>

</div>
</div>

</body>
</html>