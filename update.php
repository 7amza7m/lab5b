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
    
    // Fetch user details
    $stmt = $conn->prepare("SELECT * FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Handle form submission for update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    
    // Check if password is provided
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name = ?, password = ?, role = ? WHERE matric = ?");
        $stmt->bind_param("ssss", $name, $password, $role, $matric);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, role = ? WHERE matric = ?");
        $stmt->bind_param("sss", $name, $role, $matric);
    }
    
    if ($stmt->execute()) {
        echo "<script>alert('User updated successfully'); window.location='display.php';</script>";
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
</head>
<body>
    <h2>Update User</h2>
    <form method="post" action="">
        <table>
            <tr>
                <td>Matric No:</td>
                <td>
                    <input type="text" name="matric" 
                           value="<?php echo isset($user['matric']) ? htmlspecialchars($user['matric']) : ''; ?>" 
                           readonly>
                </td>
            </tr>
            <tr>
                <td>Name:</td>
                <td>
                    <input type="text" name="name" 
                           value="<?php echo isset($user['name']) ? htmlspecialchars($user['name']) : ''; ?>" 
                           required>
                </td>
            </tr>
            <tr>
                <td>New Password (optional):</td>
                <td><input type="password" name="password"></td>
            </tr>
            <tr>
                <td>Role:</td>
                <td>
                    <select name="role">
                        <option value="lecturer" 
                            <?php echo (isset($user['role']) && $user['role'] == 'lecturer') ? 'selected' : ''; ?>>
                            Lecturer
                        </option>
                        <option value="student" 
                            <?php echo (isset($user['role']) && $user['role'] == 'student') ? 'selected' : ''; ?>>
                            Student
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="Update">
                    <a href="display.php">Cancel</a>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>