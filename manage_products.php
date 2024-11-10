<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "buyandsell";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: home.html");
    exit();
}

// Modify the SQL query to fetch more product details
$sql = "SELECT * FROM product";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Management</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #007BFF;
            color: white;
        }

        table td {
            background-color: #f9f9f9;
        }

        table td a {
            color: red;
            text-decoration: none;
        }

        table td a:hover {
            text-decoration: underline;
        }

        .product-status {
            display: inline-block;
            padding: 5px 10px;
            font-size: 0.9rem;
            border-radius: 5px;
        }

        .active {
            background-color: #28a745;
            color: white;
        }

        .inactive {
            background-color: #dc3545;
            color: white;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            max-width: 90%;
            max-height: 90%;
            background-color: #fff;
            border-radius: 10px;
            padding: 10px;
            position: relative;
        }

        .modal-content img {
            width: 100%;
            height: auto;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #333;
            font-size: 24px;
            cursor: pointer;
        }

        footer {
            text-align: center;
            margin-top: 20px;
        }

        footer a {
            color: #007BFF;
            text-decoration: none;
            font-size: 1.1rem;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h2>Product Management</h2>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Product Image</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $product['pid']; ?></td>
                        <td><?php echo $product['pname']; ?></td>
                        <td><?php echo $product['description']; ?></td>
                        <td><?php echo "Rs" . number_format($product['price'], 2); ?></td>
                        <td>
                            <?php if (!empty($product['image_path'])) { ?>
                                <img src="<?php echo $product['image_path']; ?>" alt="Product Image" class="product-image" onclick="showModal('<?php echo $product['image_path']; ?>')">
                            <?php } else { ?>
                                No image available
                            <?php } ?>
                        </td>
                        <td>
                            <?php 
                            if ($product['status'] == 'unsold') {
                                echo "<span class='product-status unsold'>unsold</span>";
                            } else {
                                echo "<span class='product-status sold'>sold</span>";
                            }
                            ?>
                        </td>
                        <td><a href='delete_product.php?id=<?php echo $product['pid']; ?>'>Delete</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <footer>
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </footer>
    </div>

    <!-- Modal structure -->
    <div id="imageModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img id="modalImage" src="" alt="Product Image">
        </div>
    </div>

    <script>
        // Function to show modal with product image
        function showModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').style.display = 'flex';
        }

        // Function to close modal
        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>
