<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: home.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Header styles */
        header {
            background-color: #007BFF;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        header h1 {
            font-size: 2rem;
        }

        /* Navigation styles */
        nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        nav a {
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            font-size: 1.1rem;
            padding: 12px 20px;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }

        nav a:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        /* Footer styles */
        footer {
            text-align: center;
            margin-top: 40px;
        }

        footer a {
            color: #007BFF;
            text-decoration: none;
            font-size: 1rem;
            padding: 10px 20px;
            border: 1px solid #007BFF;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        footer a:hover {
            background-color: #007BFF;
            color: #fff;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            header h1 {
                font-size: 1.5rem;
            }

            nav {
                flex-direction: column;
                align-items: center;
            }

            nav a {
                font-size: 1rem;
                padding: 10px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Welcome, Admin</h1>
        </header>
        
        <nav>
            <a href="manage_users.php">Manage Users</a>
            <a href="manage_products.php">Manage Products</a>
        </nav>

        <footer>
            <a href="logout.php">Logout</a>
        </footer>
    </div>
</body>
</html>
