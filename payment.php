<?php
session_start();
if (!isset($_SESSION["id"])) {
    // If not set, redirect to home page
    header("Location: home.html");
    exit();
}
$uid = $_POST['uid'];
$seller_id = $_POST['seller_id'];
$pid = $_POST['pid'];
$amount = $_POST['amount'];

// Connect to the database
$conn = new mysqli("localhost", "root", "", "buyandsell");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Generate a unique transaction ID using UUID (or you can use uniqid as an alternative)
$transaction_id = uniqid("txn_" . time() . "_", true); // Combining timestamp and random value for uniqueness

// Get the current date
$payment_date = date("Y-m-d H:i:s");

// Insert the payment record into the payment table
$paymentStmt = $conn->prepare("INSERT INTO payment (transaction_id, amount, seller_id, buyer_id, pid, payment_date) VALUES (?, ?, ?, ?, ?, ?)");
$paymentStmt->bind_param("sidiis", $transaction_id, $amount, $seller_id, $uid, $pid, $payment_date);

if ($paymentStmt->execute()) {
    echo "<script>alert('Payment sent successfully to the seller!');</script>";
    echo "Redirecting to your dashboard...";
    // Redirect to buyer's dashboard or any other page after the payment is complete
    header("refresh:2;url=buyer.php");
} else {
    echo "Error: Could not complete the payment. Please try again.";
}

$paymentStmt->close();
$conn->close();
?>
