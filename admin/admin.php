<?php
session_start();
include("connect.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['email'] != 'admin@gmail.com'){
    header("Location: login.php");
    exit();
}

$CATEGORIES = ['coffee','cold','bakery','sandwich','pizza'];

if(isset($_POST['add_item'])){
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("INSERT INTO menuitems(itemname, category, price) VALUES(?,?,?)");
    $stmt->bind_param("ssd", $name, $category, $price);
    $stmt->execute();
    $stmt->close();
}

if(isset($_POST['update_item'])){
    $id = $_POST['mid'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("UPDATE menuitems SET itemname=?, category=?, price=? WHERE mid=?");
    $stmt->bind_param("ssdi", $name, $category, $price, $id);
    $stmt->execute();
    $stmt->close();
}

if(isset($_GET['delete_item'])){
    $id = (int)$_GET['delete_item'];
    $stmt = $conn->prepare("DELETE FROM menuitems WHERE mid=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$totalSalesQuery = mysqli_query($conn, "SELECT SUM(total) as total FROM orders");
$totalSales = mysqli_fetch_assoc($totalSalesQuery)['total'];


$orderCountQuery = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders");
$orderCount = mysqli_fetch_assoc($orderCountQuery)['count'];

$menuItems = mysqli_query($conn, "SELECT * FROM menuitems");
$users = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../website_style.css">
    <link rel="stylesheet" href="admin.css">
    <script>
        function showSection(id){
            let sections = document.querySelectorAll(".section");
            sections.forEach(sec => sec.classList.remove("active"));
            document.getElementById(id).classList.add("active");
        }
    </script>
</head>
<script>

function showSection(id){

    let sections = document.querySelectorAll(".section");

    sections.forEach(sec => {
        sec.classList.remove("active");
    });

    document.getElementById(id).classList.add("active");
}

function editItem(id){

    fetch("get_menu_item.php?id=" + id)

    .then(response => response.json())

    .then(data => {

        showSection('menu');

        document.getElementById("mid").value = data.mid;
        document.getElementById("name").value = data.itemname;
        document.getElementById("category").value = data.category;
        document.getElementById("price").value = data.price;

        document.getElementById("form-title").innerText = "Edit Menu Item";

        let btn = document.getElementById("submitBtn");

        btn.innerText = "Save Changes";

        btn.name = "update_item";

        document.getElementById("cancelBtn").style.display = "inline-block";
    });
}

function resetForm(){

    document.getElementById("menuForm").reset();

    document.getElementById("mid").value = "";

    document.getElementById("form-title").innerText = "Add Menu Item";

    let btn = document.getElementById("submitBtn");

    btn.innerText = "Add Item";

    btn.name = "add_item";

    document.getElementById("cancelBtn").style.display = "none";
}

</script>
<body>

<h1>Admin Dashboard</h1>

<div class="nav">
    <button onclick="showSection('menu')">Manage Menu</button>
    <button onclick="showSection('report')">Reports</button>
    <button onclick="showSection('users')">Users</button>
    <a href="../main_page.php"><button>View Main Site</button></a>
    <a href="logout.php"><button>Logout</button></a>
</div>

<div id="menu" class="section">
    <h2>Menu Management</h2>

<h3 id="form-title">Add Menu Item</h3>

<form method="POST" id="menuForm">

    <input type="hidden" name="mid" id="mid">

    <input type="text" name="name" id="name" placeholder="Item Name" required>

    <select name="category" id="category" required>
        <option value="" disabled selected>Select Category</option>

        <?php foreach($CATEGORIES as $cat): ?>
            <option value="<?php echo $cat; ?>">
                <?php echo $cat; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="number"
           name="price"
           id="price"
           placeholder="Price"
           required
           step="0.01">

    <button type="submit" name="add_item" id="submitBtn">
        Add Item
    </button>

    <button type="button"
            id="cancelBtn"
            style="display:none;"
            onclick="resetForm()">
        Cancel
    </button>

</form>
    <hr>

    <h3>Menu Items</h3>

    <table>
        <tr>
            <th>Item</th>
            <th>Category</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($menuItems)) { ?>
            <tr>
                <td><?php echo $row['itemname']; ?></td>
                <td><?php echo $row['category']; ?></td>
                <td>₹<?php echo $row['price']; ?></td>
                <td>
<button class="action-btn"
        onclick="editItem(<?php echo $row['mid']; ?>)">
    Edit
</button>
                    <a class="action-btn danger" href="?delete_item=<?php echo $row['mid']; ?>" onclick="return confirm('Delete this menu item?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

</div>

<div id="report" class="section active">
    <h2>Sales Report</h2>
    <p>Total Orders: <?php echo $orderCount; ?></p>
    <p>Total Revenue: ₹<?php echo $totalSales ? $totalSales : 0; ?></p>
</div>

<div id="users" class="section">

    <h2>Users</h2>

    <table>
        <tr>
            <th>Email</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Address</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($users)) { ?>
        <tr>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['phone_number']; ?></td>
            <td><?php echo $row['address']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
