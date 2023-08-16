<?php
require_once "connect.php";

session_start();

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM Patients WHERE Email='$email'";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["Password"])) {
                $_SESSION["user_id"] = $row["ID"];
                $_SESSION["username"] = $row["Name"];
                $_SESSION["user_type"] = 'Patients';
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid email or password";
            }
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Error executing query: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Login</title>
    <link rel="stylesheet" href="app-style.css">
</head>
<body>
    <header>
        <h1>Login Patient</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="adminlogin.php">Admin Login</a></li>
                <li><a href="doctorlogin.php">Doctors Login</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <form action="patientlogin.php" method="post">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" name="login" value="Login">
        </form>
        <?php if (isset($error)) { ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php } ?>
    </div>
</body>
</html>