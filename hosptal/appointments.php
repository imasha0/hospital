<?php
require_once "connect.php";
session_start();

//Redirect to login page if not logged in or not admin
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}



// Handle form submission
if (isset($_POST["submit"])) {
    $values = explode(" ", $_POST['doctor_id']);
    $doctor_id =  $values[0];
    $special_id = $values[1];
    $patient_id = $_POST['patient_id'];
    $patientName = $_SESSION['username'];
    $time = $_POST["time"];
    $date = $_POST["date"];
    $today = date("Y-m-d");

    if($date < $today) {
        $error = 'The date must be after current date';
    }else {

         // Use prepared statement to insert values
        $stmt = $conn->prepare("INSERT INTO Appointments (PatientID, DoctorID, `ApptDate`, ApptTime, PatientName, SpecID) VALUES (?, ?, ?, ?, ?, ?)");
        // Bind values to placeholders in statement
        $stmt->bind_param('iisssi', $patient_id, $doctor_id, $date, $time, $patientName, $special_id);
        
        if ($stmt->execute()) {
            $success = "Appointment added successfully";
        } else {
            $error = "Error adding appointment: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <link rel="stylesheet" href="app-style.css">
</head>
<body>
    <header>
        <h1>Appointments</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
               
                <?php if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "Admin" || $_SESSION["user_type"] == "Doctors") { ?> 
                    <li><a href="specializations.php">Specializations</a></li>
                    <li><a href="patients.php">Patients</a></li>
                    <li><a href="doctors.php">Doctors</a></li>
                 <?php } ?> 
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
    <?php if(isset($_SESSION['user_type']) &&  $_SESSION['user_type'] == 'Patients'):?> 
        <h2>Make Appointment</h2>
        <form action="appointments.php" method="post">
            <input type="hidden" name="patient_id" value="<?php echo $_SESSION["user_id"]; ?>">                
            <label for="doctor_id">Doctor</label>
            <select id="doctor_id" name="doctor_id" required>
                <?php
                $sql = "SELECT Doctors.*, Specializations.Name AS SpecName FROM Doctors JOIN Specializations ON Doctors.SpecID = Specializations.SpecID";
                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["ID"] . " " . $row["SpecID"] . "'>" . $row["Name"] . " (" . $row["SpecName"] . ")" . "</option>";
                    }
                }else {
                    $error = "<div>There are no doctors at the moment ):</div>";
                }
                ?>
            </select>
            <label for="date">Date</label>
            <input type="date" id="date" name="date" required>
            <label for="time" class="time-label">Time</label>
            <input type="time" id="time" name="time" required>
            <input type="submit" name="submit" value="Make Appointment">
        </form>
    <?php endif?>
        <?php if (isset($success)) { ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php } ?>
        <h2>Upcoming Appointments</h2>
        <?php

        $sql = "SELECT Appointments.*, Doctors.Name AS DoctorName, Specializations.Name AS SpecName FROM Appointments JOIN Doctors ON Appointments.DoctorID = Doctors.ID JOIN Specializations ON Doctors.SpecID = Specializations.SpecID WHERE  `ApptDate` >= CURDATE() ORDER BY `ApptDate`, ApptTime";
        
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Date</th><th>Time</th>";
                echo "<th>Patient Name</th>";
                echo "<th>Doctor</th>";
            echo "<th>Specialization</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["ApptDate"] . "</td><td>" . $row["ApptTime"] . "</td>";
                echo "<td>" . $row["PatientName"] . "</td>";
                echo "<td>" . $row["DoctorName"] . "</td>";
                echo "<td>" . $row["SpecName"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No upcoming appointments found</p>";
        }
        
     
    
        ?>

    </div>
</body>
</html>