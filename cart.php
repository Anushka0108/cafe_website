<?php
session_start();
include("connect.php");

$is_logged_in = isset($_SESSION['user']);

// Get cart items from session (or database if stored there)
$cart_items = isset($_SESSION['cart_items']) ? $_SESSION['cart_items'] : [];

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];  // Calculate total based on price and quantity
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart | CoffeeHouse</title>

    <link rel="stylesheet" href="website_style.css">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+AT:ital,wght@0,100..400;1,100..400&display=swap" rel="stylesheet">

</head>

<body>
    <nav>
        <h1>CoffeeHouse</h1>
        <div class="links">
            <a href="main_page.php">Home</a>
            <a href="menu.php">Menu</a>
            <a href="map.php">Store Locator</a>
            <?php if ($is_logged_in): ?>
                <a href="<?php echo ($_SESSION['user']['email'] === 'admin@gmail.com') ? 'admin/admin.php' : 'user.php'; ?>">Profile</a>
                <a href="logout.php">Sign Out</a>
            <?php else: ?>
                <a href="Sign_Up.html">Sign In</a>
            <?php endif; ?>
            <a href="cart.php" class="active"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        d="M21 6H7.05L5.94 2.68A1 1 0 0 0 4.99 2h-3v2h2.28l3.54 10.63A2 2 0 0 0 9.71 16h7.59a2 2 0 0 0 1.87-1.3l2.76-7.35c.11-.31.07-.65-.11-.92A1 1 0 0 0 21 6m-3.69 8H9.72l-2-6h11.84zM10 18a2 2 0 1 0 0 4 2 2 0 1 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 1 0 0-4">
                    </path>
                </svg></a>
        </div>
    </nav>

    <div class="cart-container">
        <h1 class="cart-title">Your Cart</h1>

        <div id="cart-items" class="cart-empty-state">
            <!-- Cart items will be dynamically inserted here -->
        </div>

        <!-- SUMMARY -->
        <div class="cart-summary">
            <div class="summary-row total">
                <span>Total</span>
                <span id="total-price">₹<?php echo $total_price; ?></span>
            </div>
        </div>

        <!-- ACTIONS -->
        <div class="cart-actions">
            <button onclick="proceedCheckout()" class="checkout-btn">Proceed to Checkout</button>
            <a href="menu.php" class="continue-btn">Continue Shopping</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 CoffeeHouse. All rights reserved.</p>
    </footer>

    <script src="script/cart.js"></script>

</body>

</html>