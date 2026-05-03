<?php
session_start();
$current = basename($_SERVER['PHP_SELF']);
include "connect.php";

$error = "";

// Handle form submission
if(isset($_POST['register'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 🔹 STEP 1: Insert into users table
    $sql = "INSERT INTO users (name, email, password)
            VALUES ('$name', '$email', '$password')";

    $result = mysqli_query($conn, $sql);

    if($result){

        // 🔹 STEP 2: Get new user id
        $user_id = mysqli_insert_id($conn);

        // 🔹 STEP 3: Get role_id for 'customer'
        $role_query = "SELECT id FROM roles WHERE role_name='customer'";
        $role_result = mysqli_query($conn, $role_query);
        $role_row = mysqli_fetch_assoc($role_result);

        $role_id = $role_row['id'];

        // 🔹 STEP 4: Assign role
        mysqli_query($conn, "INSERT INTO user_roles (user_id, role_id)
                             VALUES ('$user_id', '$role_id')");

        // 🔥 STEP 5: AUTO LOGIN USER (FIXED)
        $_SESSION['user_id'] = $user_id;
        $_SESSION['roles'] = ['customer'];
        $_SESSION['active_role'] = 'customer'; // 🔥 IMPORTANT FIX

        header("Location: dashboard.php");
        exit();

    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app">
<div class="container container--narrow">

<div class="auth-card">

<h2>Create Account</h2>
<p class="auth-sub">Register as a customer</p>

<?php if($error){ ?>
    <div class="alert alert--error"><?php echo $error; ?></div>
<?php } ?>

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
    <input type="password" name="password" required>
</div>

<div class="form-group">
    <label>Role</label>
    <input type="text" value="Customer" disabled>
</div>

<input type="submit" name="register" value="Register" class="btn btn--block">

</form>

<div class="auth-foot">
    Already have an account? <a href="login.php">Login</a>
</div>

</div>

</div>
</div>

</body>
</html>