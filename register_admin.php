<?php
// Database connection
$servername = "localhost"; // Database server
$username = "root";        // Database username
$password = "";            // Database password
$dbname = "buyandsell";    // Database name (replace it with your actual database name)

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Hash the password for security
    $hashed_password = password_hash($password,  PASSWORD_BCRYPT);

    // Prepare and execute the SQL query to insert data into the database
    $sql = "INSERT INTO admin (name, username, password) VALUES ('$name', '$email', '$hashed_password')";

    if (mysqli_query($conn, $sql)) {
        // Redirect to admin login page after successful registration
        header("Location: home.html");
        exit(); // Don't forget to exit after redirecting
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Admin - IITG Bazar</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Eye icon styles */
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        /* Styling for form */
        .form-container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        h2 {
            font-size: 24px;
            text-align: center;
            color: #333;
        }

        .form-group {
            position: relative;
            margin-bottom: 15px;
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <!-- Admin Registration Form -->
    <div class="form-container">
        <h2>Register Admin - IITG Bazar</h2>
        <form action="register_admin.php" method="POST">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required placeholder="Enter full name">
            </div>
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required placeholder="Enter email address">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required placeholder="Enter password">
                <span class="eye-icon" id="eye-icon">👁️</span> <!-- Eye Icon -->
            </div>
            <button type="submit">Register Admin</button>
        </form>
    </div>

    <script>
        // Get references to the password field and eye icon
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        // Toggle visibility when the eye icon is clicked
        eyeIcon.addEventListener('click', function() {
            if (passwordField.type === 'password') {
                passwordField.type = 'text'; // Show password
                eyeIcon.innerHTML = '👁️'; // Open eye icon (same eye icon for visibility toggle)
            } else {
                passwordField.type = 'password'; // Hide password
                eyeIcon.innerHTML = '👁️'; // Same eye icon for hidden state (you can also choose to change it)
            }
        });
    </script>

</body>
</html>
