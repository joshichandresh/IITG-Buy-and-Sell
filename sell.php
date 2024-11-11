<!DOCTYPE html>
<html>
<head>
    <style>
        td, th, input {
            text-align: left;
        }
        nav {
            margin: 27px auto 0;
            position: relative;
            width: 590px;
            height: 50px;
            background-color: #34495e;
            border-radius: 8px;
            font-size: 0;
        }
        nav a {
            line-height: 50px;
            height: 100%;
            font-size: 15px;
            display: inline-block;
            text-transform: uppercase;
            text-align: left;
            color: white;
            cursor: pointer;
        }
        a:nth-child(1) {
            width: 100px;
        }
        a:nth-child(2) {
            width: 110px;
        }
        a:nth-child(3) {
            width: 100px;
        }
        a:nth-child(4) {
            width: 160px;
        }
        a:nth-child(5) {
            width: 120px;
        }
        body {
            font-family: sans-serif;
            background: #2c3e50;
        }
        .table {
            position: absolute;
            left: 400px;
            top: 200px;
        }
    </style>
    <title>ADD PRODUCT</title>
    <link rel="stylesheet" href="register.css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet" />
</head>
<nav>
    
    <a href="home.html">Home</a>
    <a href="my_items.php">My Items</a>
   

    <a href="contact.php">Contact CC</a>
    <a href="logout.php">Logout</a>
</nav>

<body>
    <div class="main-block">
        <form action="sell.php" method="POST" enctype="multipart/form-data">
            <legend>
                <h3>Product Details</h3>
            </legend>
            <div class="account-details">
                <div><label>Name</label><input type="text" name="name" required /></div>
                <div><label>Category</label><input type="text" name="category" required /></div>
            </div>
            <div class="personal-details">
                <div>
                    <div><label>Price</label><input type="number" name="price" step="0.01" required /></div>
                    <div><label>Description</label><input type="text" name="description" required /></div>
                    <div><label>Product Image</label><input type="file" name="image" accept="image/*" required /></div>

                </div>
               
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>

<?php
session_start(); // Start the session
if (!isset($_SESSION["id"])) {
    // If not set, redirect to home page
    header("Location: home.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "buyandsell");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure email exists in session


$sid = $_SESSION['id'];

// Fetch user ID from user table based on email

if ($sid === null) {
    die("Seller ID not found for the given email.");
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $ca = mysqli_real_escape_string($conn, $_POST['category']);
    $pri = floatval($_POST['price']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);

    // Handle the image upload
    if (isset($_FILES["image"])) {
        // Define the target directory
        $target_dir = "uploads/";
        
        // Check if the directory exists, if not, create it
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        // Get the file name and sanitize it
        $filename = preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES["image"]["name"]);
        $target_file = $target_dir . basename($filename);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is an actual image
        if (getimagesize($_FILES["image"]["tmp_name"]) === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size (optional: limit to 5MB for example)
        if ($_FILES["image"]["size"] > 5000000) { // Max 5MB
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" ) {
            echo "Sorry, only JPG, JPEG, PNG files are allowed.";
            $uploadOk = 0;
        }

        // If everything is ok, try to upload the file
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
            } else {
                echo "Error uploading the image file.";
            }
        }
    } else {
        echo "No image file uploaded.";
    }

    // Prepare the SQL statement to insert the data
    $sql = "INSERT INTO product (seller_id, pname, price, description, category, image_path) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Check for statement preparation errors
    if (!$stmt) {
        die("SQL prepare error: " . $conn->error);
    }

    // Get the uploaded image path (if any)
    $imagePath = isset($target_file) ? $target_file : null;

    // Bind parameters
    $stmt->bind_param("isssss", $sid, $name, $pri, $desc, $ca, $imagePath);

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Use header redirect after outputting messages
        header("Location: my_items.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>


