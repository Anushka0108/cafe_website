<?php
session_start();
include("connect.php");

$is_logged_in = isset($_SESSION['user']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CoffeeHouse</title>
    <link rel="stylesheet" href="website_style.css">
    <script src="script/menu.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+AT:ital,wght@0,100..400;1,100..400&display=swap"
        rel="stylesheet">
</head>

<body>
<nav>
        <h1>CoffeeHouse</h1>
        <div class="links">
            <a href="main_page.php">Home</a>
            <a href="menu.php" class="active">Menu</a>
            <a href="map.php">Store Locator</a>
            <a href="user.php">Profile</a>
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
    <p class="menu-title-main">Menu</p><br><br>
    <div class="search" style="text-align:center; margin: 15px 0;">
        <input type="text" id="searchInput" placeholder="Search item..." onkeyup="searchItem()">
    </div>
    <div id="noResults" style="display:none; text-align:center; margin:40px auto; padding:20px; font-size:24px; color:white; background:var(--cream); border-radius:20px; max-width:600px;">
        No items found matching "<span id="searchTerm"></span>".
    </div>
<div class="container">

        <section class="menu-category">
            <div class="category-img">
                <img src="assets/coffeeimg.png" alt="Coffee">
            </div>

            <div class="category-items">
                <h2>Hot Coffees</h2>

                <?php
                $sql = "SELECT * FROM menuitems WHERE category='coffee'";
                $result = $conn->query($sql);

                while($row = $result->fetch_assoc()) {
                ?>
                <div class="item">
                    <h4><?php echo $row['itemname']; ?></h4>
                    <span>₹<?php echo $row['price']; ?></span>
                    <button onclick="selectItem(
                            '<?php echo $row['itemname']; ?>',
                            <?php echo $row['price']; ?>,
                            'assets/coffeeimg.png',
                            'coffee'
                        )">+</button>
                </div>
                <?php } ?>

            </div>
        </section>

        <section class="menu-category reverse">
            <div class="category-img">
                <img src="assets/coldcoffee.png" alt="Cold Drinks">
            </div>

            <div class="category-items">
                <h2>Cold Beverages</h2>

                <?php
                $sql = "SELECT * FROM menuitems WHERE category='cold'";
                $result = $conn->query($sql);

                while($row = $result->fetch_assoc()) {
                ?>
                <div class="item">
                    <h4><?php echo $row['itemname']; ?></h4>
                    <span>₹<?php echo $row['price']; ?></span>
                    <button onclick="selectItem(
                            '<?php echo $row['itemname']; ?>',
                            <?php echo $row['price']; ?>,
                            'assets/coldcoffee.png',
                            'coffee'
                        )">+</button>
                </div>
                <?php } ?>

            </div>
        </section>

        <section class="menu-category">
            <div class="category-img">
                <img src="assets/bakery.png" alt="Bakery">
            </div>

            <div class="category-items">
                <h2>Bakery Specials</h2>

                <?php
                $sql = "SELECT * FROM menuitems WHERE category='bakery'";
                $result = $conn->query($sql);

                while($row = $result->fetch_assoc()) {
                ?>
                <div class="item">
                    <h4><?php echo $row['itemname']; ?></h4>
                    <span>₹<?php echo $row['price']; ?></span>
                    <button onclick="selectItem(
                            '<?php echo $row['itemname']; ?>',
                            <?php echo $row['price']; ?>,
                            'assets/bakery.png',
                            'bakery'
                        )">+</button>
                </div>
                <?php } ?>

            </div>
        </section>

        <section class="menu-category reverse">
            <div class="category-img">
                <img src="assets/sandwich1.png" alt="Sandwich">
            </div>

            <div class="category-items">
                <h2>Sandwiches</h2>

                <?php
                $sql = "SELECT * FROM menuitems WHERE category='sandwich'";
                $result = $conn->query($sql);

                while($row = $result->fetch_assoc()) {
                ?>
                <div class="item">
                    <h4><?php echo $row['itemname']; ?></h4>
                    <span>₹<?php echo $row['price']; ?></span>
                    <button onclick="selectItem(
                            '<?php echo $row['itemname']; ?>',
                            <?php echo $row['price']; ?>,
                            'assets/sandwich1.png',
                            'sandwich'
                        )">+</button>
                </div>
                <?php } ?>

            </div>
        </section>

        <section class="menu-category">
            <div class="category-img">
                <img src="assets/pizza1.png" alt="Pizza">
            </div>

            <div class="category-items">
                <h2>Pizzas</h2>

                <?php
                $sql = "SELECT * FROM menuitems WHERE category='pizza'";
                $result = $conn->query($sql);

                while($row = $result->fetch_assoc()) {
                ?>
                <div class="item">
                    <h4><?php echo $row['itemname']; ?></h4>
                    <span>₹<?php echo $row['price']; ?></span>
                    <button onclick="selectItem(
                            '<?php echo $row['itemname']; ?>',
                            <?php echo $row['price']; ?>,
                            'assets/pizza1.png',
                            'pizza'
                        )">+</button>
                </div>
                <?php } ?>

            </div>
        </section>
    </div>

<footer>
    <p>&copy; 2024 CoffeeHouse</p>
</footer>

</body>
</html>