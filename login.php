<?php
session_start();
$current = basename($_SERVER['PHP_SELF']);
include "connect.php";

$error = "";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];
    $selected_role = $_POST['selected_role'];

    // 🔹 Step 1: Check credentials
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){

        $user = mysqli_fetch_assoc($result);
        $user_id = $user['id'];

        // 🔹 Step 2: Fetch roles
        $roles_query = "SELECT role_name 
                        FROM roles 
                        JOIN user_roles 
                        ON roles.id = user_roles.role_id
                        WHERE user_roles.user_id = '$user_id'";

        $roles_result = mysqli_query($conn, $roles_query);

        $roles = [];

        while($row = mysqli_fetch_assoc($roles_result)){
            $roles[] = $row['role_name'];
        }

        // 🔹 Step 3: Check role
        if(in_array($selected_role, $roles)){

            $_SESSION['user_id'] = $user_id;
            $_SESSION['roles'] = $roles;
            $_SESSION['active_role'] = $selected_role;

            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Access Denied! You are not registered as $selected_role.";
        }

    } else {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app">
<div class="container container--narrow">

<div class="auth-card">

<h2>Welcome Back</h2>
<p class="auth-sub">Login to your account</p>

<?php if($error){ ?>
    <div class="alert alert--error"><?php echo $error; ?></div>
<?php } ?>

<form method="POST">

<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" required>
</div>

<div class="form-group">
    <label>Password</label>
    <input type="password" name="password" required>
</div>

<div class="form-group">
    <label>Login As</label>
    <select name="selected_role">
        <option value="customer">Customer</option>
        <option value="manager">Manager</option>
        <option value="admin">Admin</option>
        <option value="waiter">Waiter</option>
        <option value="rider">Rider</option>
    </select>
</div>

<input type="submit" name="login" value="Login" class="btn btn--block">

</form>

<div class="auth-foot">
    Don’t have an account? <a href="register.php">Register</a>
</div>

</div>

</div>
</div>

</body>
</html>