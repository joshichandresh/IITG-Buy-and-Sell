<!DOCTYPE html>
<html>
<head>
    <title>Account Registration Form</title>
    <link rel="stylesheet" href="register.css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet" />
</head>
<body>
    <div class="main-block">
        <form action="register.php" method="POST">
            <h1>Create a Free Account</h1>
            <fieldset>
                <legend>
                    <h3>Account Details</h3>
                </legend>
                <div class="account-details">
                    <div><label>Email*</label><input type="text" name="email" required /></div>
                    <div><label>Password*</label><input type="password" name="password" required /></div>
                </div>
            </fieldset>
            <fieldset>
                <legend>
                    <h3>Personal Details</h3>
                </legend>
                <div class="personal-details">
                    <div>
                        <div><label>First Name*</label><input type="text" name="fname" required /></div>
                        <div><label>Last Name</label><input type="text" name="lname" /></div>
                        <div><label>Address</label><input type="text" name="address" /></div>
                        <div><label>Phone Number</label><input type="text" name="number" /></div>
                    </div>
                </div>
            </fieldset>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
<?php
$conn = new mysqli("localhost", "root", "", "buyandsell");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and validate
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $number = $_POST['number'];

    // Hash the password before insertion
    $hashed_password = password_hash($password,  PASSWORD_BCRYPT);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO user (fname, lname, email, password, contact_no, address) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    // Bind parameters
    $stmt->bind_param("ssssss", $fname, $lname, $email, $hashed_password, $number, $address);

    // Execute the statement
    if ($stmt->execute()) {
        echo "ACCOUNT CREATED. REDIRECTING...";
        header("refresh:2;url=home.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
