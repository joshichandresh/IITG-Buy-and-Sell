<?php
$uid = $_POST['uid'];  // Buyer ID
$pid = $_POST['pid'];  // Product ID
$cost = $_POST['cost'];  // Cost of the product
$bal = $_POST['balance'];  // Buyer's current wallet balance

$remain = $bal - $cost;  // Buyer's new balance

$conn = new mysqli("localhost", "root", "", "buyandsell");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the seller ID based on product ID
$sellerStmt = $conn->prepare("SELECT seller_id FROM product WHERE pid = ?");
$sellerStmt->bind_param("i", $pid);
$sellerStmt->execute();
$sellerResult = $sellerStmt->get_result();

if ($sellerRow = $sellerResult->fetch_assoc()) {
    $seller_id = $sellerRow['seller_id'];

    // Fetch the current balance of the seller
    $balanceStmt = $conn->prepare("SELECT wallet_balance FROM user WHERE uid = ?");
    $balanceStmt->bind_param("i", $seller_id);
    $balanceStmt->execute();
    $balanceResult = $balanceStmt->get_result();

    if ($balanceRow = $balanceResult->fetch_assoc()) {
        $seller_balance = $balanceRow['wallet_balance'];
        $new_seller_balance = $seller_balance + $cost;  // Seller's new balance

        // Update the buyer's wallet balance
        $updateBuyer = $conn->prepare("UPDATE user SET wallet_balance = ? WHERE uid = ?");
        $updateBuyer->bind_param("di", $remain, $uid);
        $updateBuyer->execute();

        // Update the seller's wallet balance
        $updateSeller = $conn->prepare("UPDATE user SET wallet_balance = ? WHERE uid = ?");
        $updateSeller->bind_param("di", $new_seller_balance, $seller_id);
        $updateSeller->execute();

        // Update product status to 'sold' and set buyer_id instead of deleting the product
        $updateProduct = $conn->prepare("UPDATE product SET status = 'sold', buyer_id = ? WHERE pid = ?");
        $updateProduct->bind_param("ii", $uid, $pid);
        $updateProduct->execute();
        
        echo "ITEM BOUGHT SUCCESSFULLY!! YOUR NEW BALANCE: $remain<br>";
        echo "Redirecting to payment...";

        // Store details for payment.php
        echo '
        <form id="paymentForm" action="payment.php" method="POST">
            <input type="hidden" name="uid" value="' . $uid . '">
            <input type="hidden" name="seller_id" value="' . $seller_id . '">
            <input type="hidden" name="pid" value="' . $pid . '">
            <input type="hidden" name="amount" value="' . $cost . '">
        </form>
        <script>
            setTimeout(function() {
                document.getElementById("paymentForm").submit();
            }, 2000);
        </script>';
        
    } else {
        echo "Error: Seller not found.";
    }

    $balanceStmt->close();
} else {
    echo "Error: Product not found.";
}

$sellerStmt->close();
$conn->close();
?>
