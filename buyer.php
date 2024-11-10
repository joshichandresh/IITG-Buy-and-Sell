<!DOCTYPE html>
<html>
<head>
<style>
/* General styling for the page */
body {
    font-family: Arial, sans-serif;
    background-color: #f0f4f8;
    margin: 0;
    padding: 0;
    color: #333;
}

/* Styling for navigation bar */
nav {
    display: flex;
    justify-content: center;
    gap: 10px;
    background-color: #34495e;
    padding: 15px;
    border-radius: 8px;
    margin: 20px auto;
    width: fit-content;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

nav a {
    color: white;
    text-transform: uppercase;
    text-decoration: none;
    font-size: 15px;
    padding: 10px 20px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

nav a:hover {
    background-color: #1abc9c;
}

/* Styling for the table container */
.table {
    margin: 0 auto;
    max-width: 900px;
    padding: 20px;
    background-color: #ecf0f1;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    margin-top: 30px;
}

/* Table styling */
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #34495e;
    color: white;
}

tr:hover {
    background-color: #f1f1f1;
    transition: background-color 0.2s ease;
}

td input[type="submit"] {
    background-color: #e74c3c;
    color: white;
    padding: 6px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

td input[type="submit"]:hover {
    background-color: #c0392b;
}

/* Styling for the no items message */
.no-items-message {
    text-align: center;
    font-size: 18px;
    color: #e74c3c;
    padding: 20px;
}

/* Styling for the product image */
.product-image {
    width: 100px;
    height: auto;
    border-radius: 4px;
    cursor: pointer;
}

/* Modal styling for image zoom */
.modal {
    display: none;
    position: fixed;
    z-index: 100;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.8);
    padding-top: 60px;
}

.modal-content {
    margin: auto;
    display: block;
    max-width: 80%;
    border-radius: 8px;
}

.close {
    position: absolute;
    top: 20px;
    right: 35px;
    color: white;
    font-size: 35px;
    font-weight: bold;
    cursor: pointer;
}
</style>	
</head>
<body>
	
<nav>
	<a href="home.html">Home</a>
	<a href="bought.php">Bought Items</a>
	<a href="add_money.php">Add Money</a>
	<a href="contact.php">Contact CC</a>
	<a href="logout.php">Logout</a>
</nav>
	
<div class="table">
    <?php
    session_start();
    $uid = $_SESSION["id"];
    $conn = new mysqli("localhost", "root", "", "buyandsell");
    $all = "SELECT * FROM product WHERE seller_id != $uid AND status = 'unsold'";
    $query = mysqli_query($conn, $all);

    if (mysqli_num_rows($query) > 0) {
        echo '<table>
                <tr>	
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Buy Product</th>
                </tr>';
        
        while($res = mysqli_fetch_array($query)) {
            $p = $res['pid'];
            $imagePath = $res['image_path'];
            echo '<form action="buy.php" method="POST" onsubmit="return confirmPurchase()">
                    <tr>
                        <td><img src="' . $imagePath . '" class="product-image" alt="Product Image" onclick="openModal(\'' . $imagePath . '\')"></td>
                        <td>' . $res['pname'] . '</td>
                        <td>' . $res['price'] . '</td>
                        <td>' . $res['description'] . '</td>
                        <td>' . $res['category'] . '</td>
                        <td><input type="submit" name="pid" value="' . $p . '"></td>
                    </tr>
                  </form>';
        }
        echo '</table>';
    } else {
        echo '<p class="no-items-message">No items available for purchase at the moment.</p>';
    }
    ?>
</div>

<!-- Modal for image preview -->
<div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<script>
// JavaScript function to confirm the purchase action
function confirmPurchase() {
    return confirm("Are you sure you want to purchase this product?");
}

// Function to open the modal with the clicked image
function openModal(imageSrc) {
    document.getElementById("imageModal").style.display = "block";
    document.getElementById("modalImage").src = imageSrc;
}

// Function to close the modal
function closeModal() {
    document.getElementById("imageModal").style.display = "none";
}
</script>

</body>
</html>
