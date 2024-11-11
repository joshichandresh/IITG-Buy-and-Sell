<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #fffdd0;
            margin: 0;
            padding: 0;
            color: #333;
        }

        nav {
            margin: 20px auto;
            position: relative;
            width: 80%;
            height: 50px;
            background-color: #34495e;
            border-radius: 8px;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        nav a {
            font-size: 15px;
            text-transform: uppercase;
            text-align: center;
            color: white;
            cursor: pointer;
            padding: 12px 20px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        nav a:hover {
            background-color: #1abc9c; /* Hover effect */
        }

        .table {
            position: relative;
            left: 20px;
            top: 50px;
            width: 95%;
            max-width: 1100px;
            margin: auto;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
            color: #fff;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #34495e;
        }

        td img {
            width: 120px; /* Adjusted image size */
            height: auto;
            border-radius: 4px;
            cursor: pointer;
        }

        tr:hover {
            background-color: #16a085;
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
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            max-width: 80%;
            max-height: 80%;
        }

        /* Close Button Style */
        .close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .table {
                width: 90%;
                padding: 10px;
            }

            nav {
                width: 100%;
            }

            nav a {
                padding: 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <nav>
        <a href="home.html">Home</a>
        <a href="add_money.html">Add Money</a>
        <a href="sell.php">Add Item</a>
        <a href="contact.php">Contact CC</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="table">
        <table border="1" cellspacing="7">
            <tr>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Category</th>
                <th>Status</th>
            </tr>
            <?php
            session_start();
            if (!isset($_SESSION["id"])) {
                // If not set, redirect to home page
                header("Location: home.html");
                exit();
            }
            $uid = $_SESSION['id'];
            $conn = new mysqli("localhost", "root", "", "buyandsell");

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch products
            $all = "SELECT * FROM product WHERE seller_id=$uid";
            $query = mysqli_query($conn, $all);

            // Check if the query was successful
            if (!$query) {
                die("Error in query: " . mysqli_error($conn));
            }

            // Display each product in the table
            while ($res = mysqli_fetch_array($query)) {
                $imagePath =  htmlspecialchars($res['image_path']); // Assuming 'image_path' stores the filename

                // Check if the image file exists
                if (file_exists($imagePath)) {
                    $imageHTML = "<img src='$imagePath' alt='Product Image' onclick='openModal(this)'>";
                } else {
                    $imageHTML = "<img src='uploads/default.jpg' alt='Default Image' onclick='openModal(this)'>"; // Default image if the file is not found
                }

                echo "<tr>";
                echo "<td>" . $imageHTML . "</td>";
                echo "<td>" . htmlspecialchars($res['pname']) . "</td>";
                echo "<td>" . htmlspecialchars($res['price']) . "</td>";
                echo "<td>" . htmlspecialchars($res['description']) . "</td>";
                echo "<td>" . htmlspecialchars($res['category']) . "</td>";
                echo "<td>" . htmlspecialchars($res['status']) . "</td>";
                echo "</tr>";
            }

            // Close the connection
            $conn->close();
            ?>
        </table>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="img01">
    </div>

    <script>
        // Function to open the modal
        function openModal(img) {
            var modal = document.getElementById("myModal");
            var modalImg = document.getElementById("img01");
            modal.style.display = "flex"; // Show the modal
            modalImg.src = img.src; // Set the source of the image in the modal
        }

        // Function to close the modal
        function closeModal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none"; // Hide the modal
        }

        // Close the modal if the user clicks outside of the image
        window.onclick = function(event) {
            var modal = document.getElementById("myModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
