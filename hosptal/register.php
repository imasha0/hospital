<?php
require_once "connect.php";
session_start();

// Redirect to home page if already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

// Handle form submission
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $phone = $_POST["phone"];
    $address = $_POST["address"];

    // Use prepared statement to insert values
    $stmt = $conn->prepare("INSERT INTO Patients (Name, Email, Password, Phone, Address) VALUES (?, ?, ?, ?, ?)");
    // Bind values to placeholders in statement
    $stmt->bind_param("sssss", $name, $email, $password, $phone, $address);
    if ($stmt->execute()) {
        $success = "Patient added successfully";
    } else {
        $error = "Error adding patient: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="app-style.css">
</head>
<body>
    <header>
        <h1>Registration</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="patientlogin.php">Login</a></li>
                <li><a href="doctors.php">Doctors</a></li>
                <li><a href="specializations.php">Specializations</a></li>
                <li><a href="appointments.php">Appointments</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>Register a Patient</h2>
        <form action="register.php" method="post">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" required style="margin-bottom: 10px; padding: 5px ;">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" required>
            <input type="submit" name="submit" value="Add Patient">
        </form>
        <?php if (isset($success)) { ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php } ?>
    </div>
</body>
</html>