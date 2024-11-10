<?php
session_start();
$conn = new mysqli("localhost", "root", "", "buyandsell");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current buyer's ID from session
$uid = $_SESSION["id"];

// Query 1: Fetch product details including image where status is 'sold' and buyer_id matches
$productQuery = $conn->prepare("
    SELECT p.pid, p.pname, p.category, p.description, p.price, p.seller_id, p.image_path, 
           u.email AS seller_email, u.contact_no AS seller_contact
    FROM product p
    JOIN user u ON p.seller_id = u.uid
    WHERE p.status = 'sold' AND p.buyer_id = ?
");
$productQuery->bind_param("i", $uid);
$productQuery->execute();
$productResult = $productQuery->get_result();

// Query 2: Fetch payment dates for each product (based on pid)
$paymentQuery = $conn->prepare("
    SELECT pid, payment_date FROM payment WHERE buyer_id = ?
");
$paymentQuery->bind_param("i", $uid);
$paymentQuery->execute();
$paymentResult = $paymentQuery->get_result();

// Store payment dates in an associative array
$paymentDates = [];
while ($paymentRow = $paymentResult->fetch_assoc()) {
    $paymentDates[$paymentRow['pid']] = $paymentRow['payment_date'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Purchased Items</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        color: #333;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    nav {
        width: 100%;
        background-color: #2c3e50;
        padding: 15px 0;
        display: flex;
        justify-content: center;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    nav a {
        color: white;
        text-decoration: none;
        margin: 0 20px;
        font-size: 18px;
    }

    nav a:hover {
        text-decoration: underline;
    }

    .container {
        width: 90%;
        max-width: 1000px;
        margin: 20px auto;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
    }

    h2 {
        text-align: center;
        color: #2c3e50;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    th, td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: center;
    }

    th {
        background-color: #2c3e50;
        color: #fff;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    td img {
        max-width: 100px;
        height: auto;
        cursor: pointer; /* Makes it look clickable */
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.8);
        padding-top: 60px;
        text-align: center;
    }

    .modal img {
        margin: auto;
        display: block;
        max-width: 80%;
        max-height: 80%;
    }

    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #fff;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: #f44336;
        text-decoration: none;
        cursor: pointer;
    }
</style>
</head>
<body>

<nav>
    <a href="home.html">Home</a>
    <a href="buyer.php">Continue Shopping</a>
</nav>

<div class="container">
    <h2>Your Purchased Items</h2>
    
    <?php if ($productResult->num_rows > 0): ?>
        <table>
            <tr>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Price</th>
                <th>Seller Contact</th>
                <th>Seller Email</th>
                <th>Payment Date</th>
            </tr>

            <?php 
            // Fetch product details
            while ($productRow = $productResult->fetch_assoc()):
                // Get the payment date using the pid from the associative array
                $paymentDate = isset($paymentDates[$productRow['pid']]) ? $paymentDates[$productRow['pid']] : 'Not Available';
                
                // Get the image path and create the image URL
                $imagePath =  htmlspecialchars($productRow['image_path']); // Assuming image_path stores the image filename
            ?>
                <tr>
                    <td><img src="<?php echo $imagePath; ?>" alt="Product Image" onclick="openModal('<?php echo $imagePath; ?>')"></td>
                    <td><?php echo htmlspecialchars($productRow['pname']); ?></td>
                    <td><?php echo htmlspecialchars($productRow['category']); ?></td>
                    <td><?php echo htmlspecialchars($productRow['description']); ?></td>
                    <td><?php echo htmlspecialchars($productRow['price']); ?></td>
                    <td><a href="tel:<?php echo htmlspecialchars($productRow['seller_contact']); ?>"><?php echo htmlspecialchars($productRow['seller_contact']); ?></a></td>
                    <td><a href="mailto:<?php echo htmlspecialchars($productRow['seller_email']); ?>"><?php echo htmlspecialchars($productRow['seller_email']); ?></a></td>
                    <td><?php echo htmlspecialchars($paymentDate); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align: center; color: #777;">You haven't bought any items yet.</p>
    <?php endif; ?>
</div>

<!-- Modal for displaying large image -->
<div id="myModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img id="modalImage" src="" alt="Product Image">
</div>

<script>
    // Function to open the modal with the clicked image
    function openModal(imagePath) {
        var modal = document.getElementById("myModal");
        var modalImg = document.getElementById("modalImage");
        modal.style.display = "block";
        modalImg.src = imagePath;
    }

    // Function to close the modal
    function closeModal() {
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
    }

    // Close modal if user clicks anywhere outside the image
    window.onclick = function(event) {
        var modal = document.getElementById("myModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>

<?php
// Close the statements and connection
$productQuery->close();
$paymentQuery->close();
$conn->close();
?>
