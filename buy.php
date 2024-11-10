<?php
session_start();
$conn = new mysqli("localhost", "root", "", "buyandsell");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product ID and user ID from session
$pid = $_POST['pid'];
$uid = $_SESSION["id"];//this is user who is now buyer that means inshort buyer id

// Prepare statement to fetch product details securely
$productStmt = $conn->prepare("SELECT * FROM product WHERE pid = ?");
$productStmt->bind_param("i", $pid);
$productStmt->execute();
$productResult = $productStmt->get_result();

// Prepare statement to fetch user wallet balance securely
$balanceStmt = $conn->prepare("SELECT wallet_balance FROM user WHERE uid = ?");
$balanceStmt->bind_param("i", $uid);
$balanceStmt->execute();
$balanceResult = $balanceStmt->get_result();

// Fetch product price securely
$priceStmt = $conn->prepare("SELECT price FROM product WHERE pid = ?");
$priceStmt->bind_param("i", $pid);
$priceStmt->execute();
$priceResult = $priceStmt->get_result();

// Check if product price is available
if ($priceRow = $priceResult->fetch_assoc()) {
    $cost = $priceRow['price'];
} else {
    die("Product not found.");
}

// Check if user's wallet balance is available
if ($balanceRow = $balanceResult->fetch_assoc()) {
    $balc = $balanceRow['wallet_balance'];
} else {
    die("User not found.");
}

// Display product details
while ($res = $productResult->fetch_assoc()) {
    ?>
    <div style="position:absolute; font-family: sans-serif; background: #2c3e50; top:100px; left:300px;">
        <table border="1" cellspacing="7">
            <tr>
               
                <td><?php echo htmlspecialchars($res['pname']); ?></td>
                <td><?php echo htmlspecialchars($res['price']); ?></td>
                <td><?php echo htmlspecialchars($res['description']); ?></td>
                <td><?php echo htmlspecialchars($res['category']); ?></td>
                

            </tr>
        </table>
    </div>
    <?php
}

// Check if the user has enough balance to buy the product
if ($cost > $balc) {
    ?>
    <div style="position:absolute;top:300px;left:300px;">
        NOT ENOUGH MONEY TO BUY. YOU CURRENTLY HAVE <?php echo htmlspecialchars($balc); ?>
        <form action="add_money.php" method="GET">
            <button type="submit">Add Money</button>
        </form>
    </div>
    <?php
} else {
    ?>
    <div style="position: absolute; font-family: sans-serif; background: #2c3e50; top: 300px; left: 500px;">
        <form action="buynow.php" method="POST">
            <input type="hidden" name="uid" value="<?php echo htmlspecialchars($uid); ?>">
            <input type="hidden" name="pid" value="<?php echo htmlspecialchars($pid); ?>">
            <input type="hidden" name="cost" value="<?php echo htmlspecialchars($cost); ?>">
            <input type="hidden" name="balance" value="<?php echo htmlspecialchars($balc); ?>">
           
            <button type="submit">Buy!</button>
        </form>
    </div>
    <?php
}

// Close prepared statements and connection
$productStmt->close();
$balanceStmt->close();
$priceStmt->close();
$conn->close();
?>
