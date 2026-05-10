<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user']['email'];

$stmt = $conn->prepare("SELECT full_name, address, email FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc();

$orderStmt = $conn->prepare("SELECT orderid, total FROM orders WHERE email = ? ORDER BY orderid DESC");
$orderStmt->bind_param("s", $email);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();
$orders = $orderResult->fetch_all(MYSQLI_ASSOC);

foreach ($orders as &$order) {
    $itemStmt = $conn->prepare("
    SELECT m.itemname, od.quantity
    FROM order_details od
    JOIN menuitems m ON od.mid = m.mid
    WHERE od.orderid = ?
");
    $itemStmt->bind_param("i", $order['orderid']);
    $itemStmt->execute();
    $itemResult = $itemStmt->get_result();

    $order['items'] = $itemResult->fetch_all(MYSQLI_ASSOC);

    $itemStmt->close();
}

$totalOrders = count($orders);
$totalSpent = 0;

foreach ($orders as $order) {
    $totalSpent += $order['total'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateProfile'])) {
    $updatedFullName = $_POST['full_name'];
    $updatedAddress = $_POST['address'];

    $updateStmt = $conn->prepare("UPDATE users SET full_name = ?, address = ? WHERE email = ?");
    $updateStmt->bind_param("sss", $updatedFullName, $updatedAddress, $email);
    if ($updateStmt->execute()) {
        $_SESSION['user']['full_name'] = $updatedFullName;
        $_SESSION['user']['address'] = $updatedAddress;
        header("Location: user.php");
        exit();
    } else {
        $error = "Failed to update profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>User Dashboard</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="website_style.css">
    <link rel="stylesheet" href="user.css">
</head>

<body>

    <nav>
        <h1>CoffeeHouse</h1>
        <div class="links">
            <a href="main_page.php">Home</a>
            <a href="menu.php">Menu</a>
            <a href="map.php">Store Locator</a>
            <a href="user.php" class="active">Profile</a>
            <a href="logout.php">Sign Out</a>
            <a href="cart.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21 6H7.05L5.94 2.68A1 1 0 0 0 4.99 2h-3v2h2.28l3.54 10.63A2 2 0 0 0 9.71 16h7.59a2 2 0 0 0 1.87-1.3l2.76-7.35c.11-.31.07-.65-.11-.92A1 1 0 0 0 21 6m-3.69 8H9.72l-2-6h11.84zM10 18a2 2 0 1 0 0 4 2 2 0 1 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 1 0 0-4"></path>
                </svg>
            </a>
        </div>
    </nav>

    <section class="hero">
        <div class="welcome-box">
            <h1>Welcome Back, <?php echo htmlspecialchars(explode(' ', $user['full_name'])[0]); ?> ☕</h1>
            <p>Manage your profile and view all your delicious CoffeeHouse orders.</p>

            <div class="stats">
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <span><?php echo $totalOrders; ?></span>
                </div>

                <div class="stat-card">
                    <h3>Total Spent</h3>
                    <span>₹<?php echo number_format($totalSpent, 2); ?></span>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="card">
            <h2>User Details</h2>

            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="field-group">
                    <label>Full Name</label>
                    <input type="text" class="field" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                </div>

                <div class="field-group">
                    <label>Address</label>
                    <input type="text" class="field" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required>
                </div>

                <div class="field-group">
                    <label>Email Address</label>
                    <input type="email" class="field" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" readonly>
                </div>

                <button type="submit" name="updateProfile" class="update-btn">Update Profile</button>
            </form>
        </div>

        <div class="card">
            <h2>Your Orders</h2>

            <?php if (count($orders) > 0): ?>
                <div class="orders">
                    <?php foreach ($orders as $order): ?>
                        <div class="order-item">
                            <div class="order-left">
                                <h4>Order #<?php echo htmlspecialchars($order['orderid']); ?></h4>

                                <?php if (!empty($order['items'])): ?>
                                    <ul style="margin:5px 0; padding-left:15px;">
                                        <?php foreach ($order['items'] as $item): ?>
                                            <li>
                                                <?php echo htmlspecialchars($item['itemname']); ?>
                                                (x<?php echo $item['quantity']; ?>)
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p>No items found</p>
                                <?php endif; ?>

                            </div>

                            <div class="price">
                            ₹<?php echo number_format($order['total'], 2); ?>
                            <br>
                            <a href="reorder.php?orderid=<?php echo $order['orderid']; ?>" class="action-btn">
                            Order Again
                            </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty">
                    <h3>No Orders Yet</h3>
                    <p>Your delicious coffee orders will appear here.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>© 2026 CoffeeHouse | Freshly Brewed Happiness</p>
    </footer>

</body>

</html>

<?php
if (isset($stmt)) {
    $stmt->close();
}

if (isset($orderStmt)) {
    $orderStmt->close();
}

$conn->close();
?>