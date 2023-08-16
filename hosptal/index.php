<?php
// Done
require_once "connect.php";
session_start();


// Redirect to login page if not logged in or not admin
if (!isset($_SESSION["user_id"]) && $_SESSION["user_type"] != "Admin") {
    header("Location: patientlogin.php");
    exit();
}


// Get user data
$user_id = $_SESSION["user_id"] ?? null;
if(isset($_SESSION['user_type']) && $_SESSION['user_type'] != '') {
    $sql = "SELECT * FROM " .$_SESSION['user_type']. " WHERE ID='$user_id'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
}
$user_type = isset($_SESSION['user_type']) && $_SESSION['user_type'] != null ? $_SESSION['user_type'] : '';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="app-style.css">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo $user["Name"]; ?></h1>
        <nav>
            <ul>
                <?php if ($user_type == "Admin" || $user_type == 'Doctors') { ?>
                    <li><a href="doctors.php">Doctors</a></li>
                    <li><a href="appointments.php">Appointments</a></li>
                    <li><a href="specializations.php">specializations</a></li>
                <?php } ?>
                <?php if ($user_type == "Admin" || $user_type == "Patients") { ?>
                    <li><a href="appointments.php">Appointments</a></li>
                <?php } ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        
        <h2>Welcome to the Appointment System</h2>
        <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Admin'):?>
            <p>Use the navigation menu to access your appointments or manage the system.</p>
        <?php endif?>
        <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Doctors'):
            $sql = "SELECT * FROM Appointments WHERE DoctorID ='$user_id'";        
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Date</th><th>Time</th>";
                    echo "<th>Patient Name</th>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["ApptDate"] . "</td><td>" . $row["ApptTime"] . "</td>";
                    echo "<td>" . $row["PatientName"] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No upcoming appointments found</p>";
            }
            endif ?>
    </div>
</body>
</html>