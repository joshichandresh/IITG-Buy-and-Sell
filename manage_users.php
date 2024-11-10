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

// Query to get all users
$sql = "SELECT * FROM user";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 20px;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        .nav-links {
            margin: 20px;
            padding: 10px;
            background-color: #333;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 10px;
            margin: 0 10px;
            font-size: 16px;
        }

        .nav-links a:hover {
            background-color: #555;
            border-radius: 5px;
        }

        .user-table {
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
        }

        .user-table th, .user-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .user-table th {
            background-color: #333;
            color: #fff;
        }

        .user-table td a {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        .user-table td a:hover {
            text-decoration: underline;
        }

        .status-active {
            color: green;
            font-weight: bold;
        }

        .status-inactive {
            color: red;
            font-weight: bold;
        }

        .footer-link {
            margin-top: 20px;
            font-size: 1.1rem;
        }

        .footer-link a {
            color: #007BFF;
            text-decoration: none;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Navigation links -->
<div class="nav-links">
    <a href="logout.php">Logout</a>
    <a href="manage_products.php">Manage Products</a>
</div>

<h2>User Management</h2>

<table class="user-table">
    <tr>
        <th>User ID</th>
        <th>First Name</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php while ($user = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $user['uid']; ?></td>
            <td><?php echo $user['fname']; ?></td>
            <td class="<?php echo $user['status'] === 'active' ? 'status-active' : 'status-inactive'; ?>">
                <?php echo ucfirst($user['status']); ?>
            </td>
            <td>
                <?php if ($user['status'] === 'active') { ?>
                    <a href="block_user.php?uid=<?php echo $user['uid']; ?>">Block</a>
                <?php } else { ?>
                    <a href="unblock.php?uid=<?php echo $user['uid']; ?>">Unblock</a>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>

<!-- Back to Dashboard link -->
<div class="footer-link">
    <a href="admin_dashboard.php">Back to Dashboard</a>
</div>

<?php
// Close the connection
$conn->close();
?>

</body>
</html>
