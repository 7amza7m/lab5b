<?php
// Start session and check login
session_start();
if (!isset($_SESSION['matric'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Lab_5b";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if matric is provided
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];
    
    // Prepare delete statement
    $stmt = $conn->prepare("DELETE FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    
    if ($stmt->execute()) {
        echo "<script>
                alert('User deleted successfully');
                window.location='display.php';
              </script>";
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();
?>