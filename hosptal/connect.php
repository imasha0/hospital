<?php
// Database credentials
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'hospital';

// Attempt to connect to the database
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);

// Check connection
if (!$conn) {
    die("ERROR: Could not connect to the database. " . mysqli_connect_error());
}

// Create the database if it does not exist
if (!mysqli_select_db($conn, $dbname)) {
    $sql = "CREATE DATABASE $dbname";
    if (mysqli_query($conn, $sql)) {
   
    } else {
        die("ERROR: Could not create the database: " . mysqli_error($conn));
    }
}

// Select the database
if (!mysqli_select_db($conn, $dbname)) {
    die("ERROR: Could not select the database: " . mysqli_error($conn));
}

// Create the Admin table if it does not exist
$sql = "CREATE TABLE IF NOT EXISTS Admin (
    ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50) NOT NULL,
    Email VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL COMMENT 'Password hash using SHA1'
)";
if (mysqli_query($conn, $sql)) {
  
} else {
    die("ERROR: Could not create table Users: " . mysqli_error($conn));
}

// Create the Specializations table if it does not exist
$sql = "CREATE TABLE IF NOT EXISTS Specializations (
    SpecID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50) NOT NULL,
    Description TEXT NOT NULL
)";
if (mysqli_query($conn, $sql)) {

} else {
    die("ERROR: Could not create table Specializations: " . mysqli_error($conn));
}

// Create the Doctors table if it does not exist
$sql = "CREATE TABLE IF NOT EXISTS Doctors (
    ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50) NOT NULL,
    Email VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    SpecID INT(6) UNSIGNED,
    FOREIGN KEY (SpecID) REFERENCES Specializations(SpecID)
)";
if (mysqli_query($conn, $sql)) {

} else {
    die("ERROR: Could not create table Doctors: " . mysqli_error($conn));
}

// Create the Patients table if it does not exist
$sql = "CREATE TABLE IF NOT EXISTS Patients (
    ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50) NOT NULL,
    Email VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Phone INT(11) ,
    Address varchar(255)
)";
if (mysqli_query($conn, $sql)) {

} else {
    die("ERROR: Could not create table Patients: " . mysqli_error($conn));
}

// Create the Appointments table if it does not exist
$sql = "CREATE TABLE IF NOT EXISTS Appointments (
    ApptID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    DoctorID INT(6) UNSIGNED NOT NULL,
    PatientName VARCHAR(50) NOT NULL,
    PatientID INT(6) UNSIGNED NOT NULL,
    ApptDate DATETIME NOT NULL,
    ApptTime Time NOT NULL,
    SpecID INT(6) UNSIGNED NOT NULL,
    FOREIGN KEY (DoctorID) REFERENCES Doctors(ID),
    FOREIGN KEY (SpecID) REFERENCES Specializations(SpecID)
)";
if (mysqli_query($conn, $sql)) {

} else {
    die("ERROR: Could not create table Appointments: " . mysqli_error($conn));
}
?>