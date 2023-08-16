<?php
// Require the database connection to be established
require_once "connect.php";

// Start the session
session_start();

// Redirect to login page if not logged in or not admin
if (!isset($_SESSION["user_id"]) && $_SESSION["user_type"] != "Admin") {
    header("Location: doctorlogin.php");
    exit();
}

// Handle form submission
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $description = $_POST["description"];

    try {
        $stmt = $conn->prepare("INSERT INTO Specializations (Name, Description) VALUES (?, ?)");
        $stmt->execute(array($name, $description));
        $success = "Specialization added successfully";

    }catch(PDOException $error) {
        $error = "Error adding specialization: " . $conn->error;
    }
  
}

// Retrieve the list of specializations from the database
$sql = "SELECT * FROM Specializations";
$result = $conn->query($sql);
$specializations = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $specializations[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Specializations</title>
    <link rel="stylesheet" href="app-style.css">
</head>

<body>
    <header>
        <h1>Specializations</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="doctors.php">Doctors</a></li>
                <li><a href="appointments.php">Appointments</a></li>
                <li><a href="patients.php">Patients</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
    <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Admin'):?> 
        <h2>Add Specialization</h2>
        <form action="specializations.php" method="post">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>
            <input type="submit" name="submit" value="Add">
        </form>
    <?php endif?>
        <?php if (isset($success)) { ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php } ?>
        <h2>Current Specializations</h2>
        <?php if (count($specializations) > 0) { ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                </tr>
                <?php foreach ($specializations as $specialization) { ?>
                    <tr>
                        <td><?php echo $specialization["Name"]; ?></td>
                        <td><?php echo $specialization["Description"]; ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>No specializations found.</p>
        <?php } ?>
    </div>
</body>

</html>