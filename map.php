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
  <title>Store Locator</title>
  <link href="https://fonts.googleapis.com/css2?family=Playwrite+AT:ital,wght@0,100..400;1,100..400&display=swap"
    rel="stylesheet">

  <!-- External CSS -->
  <link rel="stylesheet" href="styles.css">
</head>

<body>

    <nav>
        <h1>CoffeeHouse</h1>
        <div class="links">
            <a href="main_page.php" >Home</a>
            <a href="menu.php">Menu</a>
            <a href="map.php" class="active">Store Locator</a>
              <a href="Admin.php" class="active">Profile</a>
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

  <!-- HEADER -->
  <div class="header">Find a CoffeeHouse Café</div>

  <!-- SEARCH BOX -->
  <input type="text" id="searchInput" placeholder="Search location...">

  <!-- STORE LIST -->
  <div class="store-list">

    <!-- STORE 1 -->
    <div class="store" data-name="baga beach">
      <h3>Baga Beach</h3>
      <p>Goa</p>
      ⭐ 4.6 · Open

      <div class="badges">
        <span class="badge">Pickup</span>
        <span class="badge">Delivery</span>
      </div>

      <div class="actions">
        <a href="https://www.google.com/maps/dir/?api=1&destination=15.5557,73.7517" target="_blank">
          Get Directions
        </a>
      </div>
    </div>

    <!-- STORE 2 -->
    <div class="store" data-name="calangute">
      <h3>Calangute</h3>
      <p>North Goa</p>
      ⭐ 4.5 · Open

      <div class="badges">
        <span class="badge">Pickup</span>
      </div>

      <div class="actions">
        <a href="https://www.google.com/maps/dir/?api=1&destination=15.5494,73.7553" target="_blank">
          Get Directions
        </a>
      </div>
    </div>

  </div>

  <!-- SEARCH SCRIPT -->
  <script>
    const searchInput = document.getElementById("searchInput");
    const stores = document.querySelectorAll(".store");

    searchInput.addEventListener("keyup", function () {
      const value = this.value.toLowerCase();

      stores.forEach(store => {
        const name = store.getAttribute("data-name");

        if (name.includes(value)) {
          store.style.display = "block";
        } else {
          store.style.display = "none";
        }
      });
    });
  </script>
  <footer>
    <p>&copy; 2024 CoffeeHouse. All rights reserved.</p>
  </footer>
</body>

</html>