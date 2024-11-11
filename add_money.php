<?php
session_start();
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

// Check if user is logged in
if (!isset($_SESSION["id"])) {
    die("User is not logged in.");
}

$uid = $_SESSION["id"]; //buyer

// Check if the form was submitted and has a valid amount
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['money']) && is_numeric($_POST['money']) && $_POST['money'] > 0) {
    $addAmount = (float)$_POST['money'];
    
    // Fetch current wallet balance
    $balanceStmt = $conn->prepare("SELECT wallet_balance FROM user WHERE uid = ?");
    $balanceStmt->bind_param("i", $uid);
    $balanceStmt->execute();
    $balanceResult = $balanceStmt->get_result();

    if ($balanceRow = $balanceResult->fetch_assoc()) {
        $currentBalance = (float)$balanceRow['wallet_balance'];
        
        // Calculate new balance
        $newBalance = $currentBalance + $addAmount;
        
        // Update wallet balance in database
        $updateStmt = $conn->prepare("UPDATE user SET wallet_balance = ? WHERE uid = ?");
        $updateStmt->bind_param("di", $newBalance, $uid);
        
        if ($updateStmt->execute()) {
            // Update session balance
            $_SESSION["bal"] = $newBalance;
            echo "Money added successfully. New balance: " . $newBalance;
            header("refresh:2;url=buyer.php");
          
        } else {
            echo "Error updating wallet balance.";
            header("refresh:2;url=buyer.php");
        }

        // Close the update statement
        $updateStmt->close();
    } else {
        echo "User not found.";
        header("refresh:2;url=home.php");
    }

    // Close the balance statement
    $balanceStmt->close();
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Display error only if form was submitted with invalid data
    echo "Invalid amount entered.";
}

// Close the connection
$conn->close();
?>

<div style="position:absolute; font-family: sans-serif; background: #2c3e50; top:300px; left:500px;">
    <form action="add_money.php" method="POST">
        <label for="money">Add Money:</label>
        <input type="number" name="money" id="money" required min="1" placeholder="Enter amount">
        <button type="submit">Add!</button>
    </form>
</div>
