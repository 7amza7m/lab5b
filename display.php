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

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to retrieve specific columns
$sql = "SELECT matric, name, role FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>User List</h2>
    <p>Welcome, <?php echo $_SESSION['matric']; ?> (<?php echo $_SESSION['role']; ?>)</p>
    <a href="logout.php">Logout</a>
    <table>
<tr>
    <th>Matric No</th>
    <th>Name</th>
    <th>Level</th>
    <th>Actions</th>
</tr>
<?php
//Update the table

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["matric"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
        echo "<td>
                <a href='update.php?matric=" . urlencode($row["matric"]) . "'>Update</a> | 
                <a href='delete.php?matric=" . urlencode($row["matric"]) . "' 
                   onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>
              </td>";
        echo "</tr>";
    }
} else {
            echo "<tr><td colspan='3'>No users found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>