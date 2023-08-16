<?php
require_once "connect.php";
session_start();

// Redirect to login page if not logged in or not admin
if (!isset($_SESSION["user_id"]) && $_SESSION["user_type"] != "Admin") {
    header("Location: doctorlogin.php");
    exit();
}

// Get specialization options for dropdown list
$spec_options = "";
$sql = "SELECT * FROM Specializations";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $spec_options .= "<option value='" . $row["SpecID"] . "'>" . $row["Name"] . "</option>";
    }
}else {
    $error = "<div>There are no specializations at the moment ):</div>";
}

// Handle form submission
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $spec_id = $_POST["spec_id"];

    $sql = "INSERT INTO Doctors (Name, Email, Password, SpecID) VALUES ('$name', '$email', '$password', '$spec_id')";
    if ($conn->query($sql) === TRUE) {
        $success = "Doctor added successfully";
    } else {
        $error = "Error adding doctor: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors</title>
    <link rel="stylesheet" href="app-style.css">
</head>
<body>
    <header>
        <h1>Doctors</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="appointments.php">Appointments</a></li>
                <li><a href="patients.php">Patients</a></li>
                <li><a href="specializations.php">specializations</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Admin'):?> 
            <h2>Add Doctor</h2>
            <form action="doctors.php" method="post">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <label for="spec_id">Specialization</label>
                <select id="spec_id" name="spec_id" required>
                    <?php echo $spec_options ?>
                </select>
                <input type="submit" name="submit" value="Add">
            </form>
        <?php endif?>
        <?php if (isset($success)) { ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php } ?>
        <h2>Current Doctors</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Specialization</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT Doctors.*, Specializations.Name AS SpecName FROM Doctors JOIN Specializations ON Doctors.SpecID = Specializations.SpecID";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["Name"] . "</td>";
                        echo "<td>" . $row["Email"] . "</td>";
                        echo "<td>" . $row["SpecName"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No doctors found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>