<?php
session_start();
$current = basename($_SERVER['PHP_SELF']);
include "connect.php";

// 🔒 Only manager allowed
if(!isset($_SESSION['user_id']) || $_SESSION['active_role'] != 'manager'){
    echo "Access Denied!";
    exit();
}

$message = "";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager Panel</title>
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
        <span class="badge badge--role">manager</span>
        <a href="logout.php" class="btn btn--ghost btn--sm">Logout</a>
    </div>
</nav>

<div class="container">

<div class="page-header">
    <h1>Manager Panel</h1>
    <p class="subtitle">Manage staff and assign roles</p>
</div>

<?php if($message){ ?>
    <div class="alert alert--success"><?php echo $message; ?></div>
<?php } ?>

<div class="card-grid">

<!-- ================= CREATE STAFF ================= -->
<div class="card">

<h3>Create Staff Account</h3>

<form method="POST">

<div class="form-group">
<label>Name</label>
<input type="text" name="name" required>
</div>

<div class="form-group">
<label>Email</label>
<input type="email" name="email" required>
</div>

<div class="form-group">
<label>Password</label>
<input type="text" name="password" required>
</div>

<div class="form-group">
<label>Role</label>
<select name="role">
    <option value="admin">Admin</option>
    <option value="waiter">Waiter</option>
    <option value="rider">Rider</option>
</select>
</div>

<input type="submit" name="create_staff" value="Create Staff" class="btn btn--block">

</form>

</div>

<!-- ================= ASSIGN ROLE ================= -->
<div class="card">

<h3>Assign Role</h3>

<form method="POST">

<div class="form-group">
<label>User ID</label>
<input type="number" name="user_id" required>
</div>

<div class="form-group">
<label>Role</label>
<select name="new_role">
    <option value="admin">Admin</option>
    <option value="waiter">Waiter</option>
    <option value="rider">Rider</option>
</select>
</div>

<input type="submit" name="assign_role" value="Assign Role" class="btn btn--block">

</form>

</div>

</div>

<!-- ================= USERS LIST ================= -->

<div class="card mt-3">
<h3>All Users</h3>

<?php
$users = mysqli_query($conn, "SELECT * FROM users");

while($u = mysqli_fetch_assoc($users)){
    echo "<div class='list-item'>";
    echo "<strong>ID:</strong> ".$u['id']." | ";
    echo "<strong>Name:</strong> ".$u['name']." | ";
    echo "<strong>Email:</strong> ".$u['email'];
    echo "</div>";
}
?>

</div>

</div>
</div>

</body>
</html>

<?php

// ================= CREATE STAFF =================
if(isset($_POST['create_staff'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    mysqli_query($conn, "INSERT INTO users (name, email, password)
                         VALUES ('$name','$email','$password')");

    $user_id = mysqli_insert_id($conn);

    $role_result = mysqli_query($conn, "SELECT id FROM roles WHERE role_name='$role'");
    $role_row = mysqli_fetch_assoc($role_result);
    $role_id = $role_row['id'];

    mysqli_query($conn, "INSERT INTO user_roles (user_id, role_id)
                         VALUES ('$user_id','$role_id')");

    echo "<script>alert('Staff created successfully');</script>";
}

// ================= ASSIGN ROLE =================
if(isset($_POST['assign_role'])){

    $user_id = $_POST['user_id'];
    $role = $_POST['new_role'];

    $role_result = mysqli_query($conn, "SELECT id FROM roles WHERE role_name='$role'");
    $role_row = mysqli_fetch_assoc($role_result);
    $role_id = $role_row['id'];

    mysqli_query($conn, "INSERT INTO user_roles (user_id, role_id)
                         VALUES ('$user_id','$role_id')");

    echo "<script>alert('Role assigned successfully');</script>";
}
?>