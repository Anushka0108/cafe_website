<?php
session_start();
include("connect.php");

$is_logged_in = isset($_SESSION['user']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customize Item</title>
    <link rel="stylesheet" href="website_style.css">

</head>

<body>

    <nav>
        <h1>CoffeeHouse</h1>
        <div class="links">
            <a href="main_page.php" >Home</a>
            <a href="menu.php">Menu</a>
            <a href="map.php">Store Locator</a>
            <?php if ($is_logged_in): ?>
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

    <div class="card">

        <!-- BACK BUTTON -->
        <a href="menu.php"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                viewBox="0 0 24 24">
                <path
                    d="M14 8h-1V4c0-.39-.23-.75-.59-.91a.98.98 0 0 0-1.07.17l-9 7.99c-.21.19-.34.46-.34.75s.12.56.34.75l9 8c.29.26.72.33 1.07.17.36-.16.59-.52.59-.91v-4h.64c1.83 0 3.54.82 4.69 2.25l1.9 2.37a1 1 0 0 0 .78.38q.165 0 .33-.06c.4-.14.67-.52.67-.94v-4c0-4.41-3.59-8-8-8Zm6 9.15-.12-.15c-1.53-1.91-3.8-3-6.25-3h-2.64v3.77L4.5 12l6.49-5.77V10h3c3.31 0 6 2.69 6 6v1.15Z">
                </path>
            </svg> </a>

        <div class="card-container">

            <div class="image-section">
                <img src="assets/cappacino.png" alt="Item Image" width="250">
            </div>

            <div class="info-section">

                <h2>Cappuccino</h2>
                <p>Base Price: ₹220</p>

                <form>
                    <!-- SIZE -->
                    <div id="size-section">
                        <h3>Select Size</h3>

                        <div class="options-group">
                            <label class="option">
                                <input type="radio" name="size" checked>
                                <span>Small (+₹0)</span>
                            </label>

                            <label class="option">
                                <input type="radio" name="size">
                                <span>Medium (+₹30)</span>
                            </label>

                            <label class="option">
                                <input type="radio" name="size">
                                <span>Large (+₹60)</span>
                            </label>
                        </div>
                    </div>

                    <!-- MILK -->
                    <div id="milk-section">
                        <h3>Choose Milk</h3>

                        <div class="options-group">
                            <label class="option">
                                <input type="radio" name="milk" checked>
                                <span>Dairy</span>
                            </label>

                            <label class="option">
                                <input type="radio" name="milk">
                                <span>Almond (+₹20)</span>
                            </label>

                            <label class="option">
                                <input type="radio" name="milk">
                                <span>Soy (+₹20)</span>
                            </label>
                        </div>
                    </div>

                    <!-- SUGAR -->
                    <div id="sugar-section">
                        <h3>Sugar Level</h3>

                        <div class="options-group">
                            <label class="option">
                                <input type="radio" name="sugar">
                                <span>No Sugar</span>
                            </label>

                            <label class="option">
                                <input type="radio" name="sugar">
                                <span>Less Sugar</span>
                            </label>

                            <label class="option">
                                <input type="radio" name="sugar" checked>
                                <span>Normal</span>
                            </label>
                        </div>
                    </div>

                    <!-- EXTRAS -->
                    <div id="extras-section">
                        <h3>Add Extras</h3>

                        <div class="options-group">
                            <label class="option">
                                <input type="checkbox">
                                <span>Cheese (+₹30)</span>
                            </label>

                            <label class="option">
                                <input type="checkbox">
                                <span>Sauce (+₹25)</span>
                            </label>
                        </div>
                    </div>

                    <!-- PIZZA -->
                    <div id="pizza-section">
                        <h3>Pizza Toppings</h3>

                        <div class="options-group">
                            <label class="option">
                                <input type="checkbox">
                                <span>Extra Cheese</span>
                            </label>

                            <label class="option">
                                <input type="checkbox">
                                <span>Olives</span>
                            </label>

                            <label class="option">
                                <input type="checkbox">
                                <span>Jalapenos</span>
                            </label>
                        </div>
                    </div>

                    <!-- QUANTITY -->
                    <div id="quantity-section">
                        <h3>Quantity</h3>
                        <input type="number" min="1" value="1">
                    </div>

                    <!-- INSTRUCTION -->
                    <div id="instruction-section">
                        <h3>Special Instructions</h3>
                        <textarea rows="3"></textarea>
                    </div>

                    <br><br>

                    <button type="button" class="checkout-btn" onclick="addToCart(this)">
                        Add to Cart
                    </button>

                </form>

            </div>

        </div>

    </div>

    <!-- ✅ FIXED SCRIPT PATH -->
    <script src="script\item.js"></script>

</body>

</html>