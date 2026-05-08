<?php
session_start();
include("connect.php");

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get logged-in user's email from session
$email = $_SESSION['user']['email'];

// Fetch user details
$stmt = $conn->prepare("SELECT full_name, address, email FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc();

// Fetch user's orders
$orderStmt = $conn->prepare("SELECT orderid, total FROM orders WHERE email = ?");
$orderStmt->bind_param("s", $email);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();
$orders = $orderResult->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>
    <title>User Dashboard</title>

    <style>
         :root {
        --brown: #4B2E1E;
        --coffee: #6F4E37;
        --latte: #CFAE8E;
        --cream: #F6EDE6;
        --light: #FBF6F1;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Segoe UI, sans-serif;
        background-color: var(--coffee);
        color: var(--brown);
        padding-top: 80px; /* IMPORTANT */
    }

    /* navigation */
 
nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: var(--cream);
    padding: 20px;
    position: sticky;
    top: 0;
    z-index: 1000;
}

nav h1 {
    font-size: 24px;
    color: var(--coffee);
}

nav .links {
    display: flex;
    gap: 20px;
}

nav a {
    text-decoration: none;
    color: var(--brown);
    padding: 10px 15px;
    border-radius: 20px;
    transition: 0.3s;
}

nav a:hover {
    color: rgb(215, 120, 120);
}

nav a.active {
    color: rgb(215, 120, 120);
}

        h1 {
            text-align: center;
            margin-top: 20px;
        }
        .container {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }
        .panel {
            background-color: #6F4E37;
            color: white;
            padding: 20px;
            width: 45%;
            border-radius: 8px;
        }
        .panel input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            border: none;
            margin-bottom: 20px;
        }
        .panel button {
            padding: 10px 20px;
            background-color: #CFAE8E;
            color: #4B2E1E;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .panel button:hover {
            background-color: #F6EDE6;
        }
        footer {
            background-color: #6F4E37;
            color: white;
            padding: 10px;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <nav>
        <h1>CoffeeHouse</h1>
        <div class="links">
            <a href="main_page.php" class="active">Home</a>
            <a href="menu.php">Menu</a>
            <a href="map.php">Store Locator</a>
<a href="user.php">Profile</a>

        <?php if (isset($_SESSION['user'])): ?>
            <a href="logout.php">Sign Out</a>
        <?php else: ?>
            <a href="Sign_Up.html">Sign In</a>
        <?php endif; ?>
            <a href="cart.php"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        d="M21 6H7.05L5.94 2.68A1 1 0 0 0 4.99 2h-3v2h2.28l3.54 10.63A2 2 0 0 0 9.71 16h7.59a2 2 0 0 0 1.87-1.3l2.76-7.35c.11-.31.07-.65-.11-.92A1 1 0 0 0 21 6m-3.69 8H9.72l-2-6h11.84zM10 18a2 2 0 1 0 0 4 2 2 0 1 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 1 0 0-4">
                    </path>
                </svg></a>
        </div>
    </nav>

    <h1>User Dashboard</h1>

    <div class="container">
        <!-- User Details Panel -->
        <div class="panel">
            <h2>User Details</h2>
            <input type="text" id="name" value="<?php echo htmlspecialchars($user['full_name']); ?>" readonly>
            <input type="text" id="address" value="<?php echo htmlspecialchars($user['address']); ?>" readonly>
            <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
        </div>

        <!-- Orders Panel -->
        <div class="panel">
            <h2>Your Orders</h2>
            <ul id="orders">
                <?php if (count($orders) > 0): ?>
                    <?php foreach ($orders as $order): ?>
                        <li>Order ID: <?php echo $order['orderid']; ?> - ₹<?php echo $order['total']; ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No orders found.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <footer>
        <p>© 2026 CoffeeHouse | Freshly Brewed Happiness</p>
    </footer>
</body>

</html>

<?php
// Close the database connections
$stmt->close();
$orderStmt->close();
$conn->close();
?>