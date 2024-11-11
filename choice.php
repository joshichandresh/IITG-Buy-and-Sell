<?php
$conn = new mysqli("localhost", "root", "", "buyandsell");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user input
if (!isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['choice'])) {

    //echo "Please enter email, password, and choice.";
    header("refresh:2;url=home.html");
    exit();
}

$email = $_POST['email'];
$password = $_POST['password'];
$c = $_POST['choice'];

// Check if the choice is admin, then check credentials from the admin table
if ($c == 'admin') {
    // Prepare SQL query to retrieve the admin's hashed password
    $q = "SELECT admin_ID, password FROM admin WHERE username = ?";
    $stmt = $conn->prepare($q);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $r = $stmt->get_result();

    // Check if the admin exists
    if ($r->num_rows == 0) {
        echo "Wrong username for admin.";
        header("refresh:2;url=home.html");
        exit();
    }

    // Fetch the admin data
    $res = $r->fetch_assoc();
    $admin_hashed_password = $res['password'];

    // Verify the entered password against the hashed password
    if (!password_verify($password, $admin_hashed_password)) {
        echo "Incorrect password for admin.";
        header("refresh:2;url=home.html");
        exit();
    }

    // Admin login success, start the session
    session_start();
    $_SESSION['admin_email'] = $email;
    $_SESSION["admin_id"] = $res['admin_ID']; // Admin's user ID
    header("Location: admin_dashboard.php");
    exit();
} else {
    // For user (buyer or seller), fetch credentials from the user table
    $q = "SELECT uid, password, wallet_balance, status FROM user WHERE email = ?";
    $stmt = $conn->prepare($q);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $r = $stmt->get_result();

    // Check if the user exists
    if ($r->num_rows == 0) {
        echo "Wrong email ID.";
        header("refresh:2;url=home.html");
        exit();
    }

    // Fetch the user data
    $res = $r->fetch_assoc();
    $user_hashed_password = $res['password'];

    // Verify the entered password against the hashed password
    if (!password_verify($password, $user_hashed_password)) {
        echo "Incorrect password.";
        header("refresh:2;url=home.html");
        exit();
    }

    // Check if the user is blocked
    if ($res['status'] == 'blocked') {
        // Fetch the admin's email (since we only have one admin)
        $admin_query = "SELECT username FROM admin LIMIT 1";
        $admin_result = $conn->query($admin_query);

        if ($admin_result->num_rows > 0) {
            $admin = $admin_result->fetch_assoc();
            $admin_email = $admin['username'];
            echo "<p>Your account has been blocked.</p>";
            echo "<p>If you have any queries, please contact the admin at <a href='mailto:$admin_email'>$admin_email</a></p>";
            echo "<a href='home.html'>Go back to Home</a>";
            exit();
        } else {
            echo "Unable to fetch admin contact information.";
            exit();
        }
    }

    // User login success, start the session
    session_start();
    $_SESSION['email'] = $email;
    $_SESSION["id"] = $res['uid']; // This is either buyer or seller ID
    $_SESSION["bal"] = $res['wallet_balance']; // User's wallet balance

    // Redirect based on choice (buyer or seller)
    switch ($c) {
        case 'buyer':
            header("Location: buyer.php");
            break;
        case 'seller':
            header("Location: sell.php");
            break;
        default:
            echo "Invalid user choice.";
            exit();
    }
    exit();
}

// Close the statement if it was created successfully
if ($stmt) {
    $stmt->close();
}

// Close the connection
$conn->close();
?>
