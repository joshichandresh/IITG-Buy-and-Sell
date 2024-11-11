<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    // If not set, redirect to home page
    header("Location: home.html");
    exit();
}
$conn = new mysqli("localhost", "root", "", "buyandsell");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: home.html");
    exit();
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Check if the product is linked to any payment
    $payment_check = "SELECT * FROM payment WHERE pid = ?";
    $stmt = $conn->prepare($payment_check);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "Cannot delete product. It's linked to a payment.";
        $status = "error";
    } else {
        // Delete the product if not linked to payment
        $delete_product = "DELETE FROM product WHERE pid = ?";
        $stmt = $conn->prepare($delete_product);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $message = "Product deleted successfully.";
            $status = "success";
        } else {
            $message = "Error deleting product.";
            $status = "error";
        }
    }
} else {
    $message = "Product ID is required.";
    $status = "error";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .success {
            color: green;
            font-size: 18px;
            font-weight: bold;
        }
        .error {
            color: red;
            font-size: 18px;
            font-weight: bold;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #4CAF50;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <?php if (isset($message)) : ?>
        <div class="<?php echo $status; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <a href="manage_products.php" class="back-btn">Back to manage_products</a>
</div>

</body>
</html>
