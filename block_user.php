<?php
// db_connection.php
session_start();
if (!isset($_SESSION["id"])) {
    // If not set, redirect to home page
    header("Location: home.html");
    exit();
}
$servername = "localhost";  // Server name
$username = "root";         // Database username
$password = "";             // Database password
$dbname = "buyandsell";     // Database name

// Create a new MySQL connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();


// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: home.html");
    exit();
}

// Check if the 'id' parameter is provided in the URL
if (isset($_GET['uid'])) {
    $user_id = $_GET['uid'];

    // Update the user's status to 'blocked'
    $sql = "UPDATE user SET status = 'blocked' WHERE uid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "User blocked successfully.";
        header("refresh:1;url=manage_users.php");


    } else {
        echo "Failed to block user.";
    }

    $stmt->close();
} else {
    echo "User ID is required.";
}

// Close the database connection
$conn->close();
?>

