<?php
require_once "connect.php";
session_start();

// Redirect to login page if not logged in or not admin
if (!isset($_SESSION["user_id"]) && $_SESSION["user_type"] != "Admin") {
    header("Location: doctorlogin.php");
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

// Get list of patients
$sql = "SELECT * FROM Patients";
$result = $conn->query($sql);
$patients = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients</title>
    <link rel="stylesheet" href="app-style.css">
</head>
<body>
    <header>
        <h1>Patients</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="doctors.php">Doctors</a></li>
                <li><a href="appointments.php">Appointments</a></li>
                <li><a href="specializations.php">specializations</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
    <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Admin'):?> 
        <h2>Add Patient</h2>
        <form action="patients.php" method="post">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" required>
            <label for="address">Address</label>
            <input type="text" id="address" name="address" required>
            <input type="submit" name="submit" value="Add Patient">
        </form>
    <?php endif?>
        <?php if (isset($success)) { ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php } ?>
        <h2>List of Patients</h2>
        <?php if (count($patients) > 0) { ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                </tr>
                <?php foreach ($patients as $patient) { ?>
                    <tr>
                        <td><?php echo $patient["Name"]; ?></td>
                        <td><?php echo $patient["Email"]; ?></td>
                        <td><?php echo $patient["Phone"]; ?></td>
                        <td><?php echo $patient["Address"]; ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>No patients found</p>
        <?php } ?>
    </div>
</body>
</html>