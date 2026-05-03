<?php
session_start();
$current = basename($_SERVER['PHP_SELF']);
include "connect.php";

// 🔒 Check login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// 🔒 Check cart
if(empty($_SESSION['cart'])){
    echo "Cart is empty!";
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<h2>Place Order</h2>

<form method="POST">

Order Type:<br>
<select name="order_type" required>
    <option value="delivery">Delivery</option>
    <option value="dine-in">Dine-in</option>
</select><br><br>

Address (for delivery):<br>
<input type="text" name="address"><br><br>

Table No (for dine-in):<br>
<input type="number" name="table_no"><br><br>

Payment Method:<br>
<select name="payment_method">
    <option value="cash">Cash</option>
    <option value="card">Card</option>
</select><br><br>

<input type="submit" name="place_order" value="Confirm Order">

</form>

<hr>

<?php

if(isset($_POST['place_order'])){

    $order_type = $_POST['order_type'];
    $address = $_POST['address'];
    $table_no = $_POST['table_no'];
    $payment = $_POST['payment_method'];

    // 🔹 Step 1: Insert order
    $insert_order = "INSERT INTO orders 
        (user_id, order_type, address, table_no, payment_method, status)
        VALUES 
        ('$user_id', '$order_type', '$address', '$table_no', '$payment', 'Pending')";

    mysqli_query($conn, $insert_order);

    // 🔹 Step 2: Get order ID
    $order_id = mysqli_insert_id($conn);

    // 🔹 Step 3: Insert items
    foreach($_SESSION['cart'] as $menu_id => $qty){

        $insert_item = "INSERT INTO order_items 
                        (order_id, menu_id, quantity)
                        VALUES 
                        ('$order_id', '$menu_id', '$qty')";

        mysqli_query($conn, $insert_item);
    }

    // 🔹 Step 4: Clear cart
    unset($_SESSION['cart']);

    echo "<h3>Order placed successfully!</h3>";
}
?>