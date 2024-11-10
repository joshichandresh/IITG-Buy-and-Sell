<?php
session_start();

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "buyandsell";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: home.html");
    exit();
}

// Check if the user ID is set in the URL
if (isset($_GET['uid'])) {
    $user_id = $_GET['uid'];

    // Update the user's status to 'active'
    $sql = "UPDATE user SET status = 'active' WHERE uid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        echo "User unblocked successfully.";
        header("refresh:1;url=manage_users.php");
    } else {
        echo "Failed to unblock user.";
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo "User ID is required.";
}

// Close the database connection
$conn->close();

// Redirect back to the user management page

exit();
?>
