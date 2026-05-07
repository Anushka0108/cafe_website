<?php 
session_start(); 
include("connect.php"); 
$is_logged_in = isset($_SESSION['user']);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>

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
            font-family: Segoe UI, sans-serif;
        }

        /* ✅ PAGE BACKGROUND */
        body {
            background: var(--coffee);
            padding-top: 80px;
        }

        /* ✅ NAVBAR */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--cream);
            padding: 15px 25px;
            position: fixed;
            top: 0;
            width: 100%;
        }

        nav h1 {
            color: var(--brown);
        }

        .links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        nav a {
            text-decoration: none;
            color: var(--brown);
        }

        nav a:hover {
            color: var(--coffee);
        }

        /* ✅ MAIN HEADING */
        h1 {
            text-align: center;
            color: white;
            margin-top: 20px;
        }

        /* ✅ LAYOUT */
        .container {
            display: flex;
            gap: 25px;
            padding: 30px;
        }

        /* ✅ PANELS (COFFEE COLOR) */
        .panel {
            background: var(--brown);   /* coffee */
            width: 50%;
            padding: 20px;
            border-radius: 20px;
            color: white;               /* text white */
        }

        /* headings inside panel */
        .panel h2,
        .panel h3 {
            color: white;
        }

        /* user list */
        .user {
            padding: 10px;
            cursor: pointer;
            border-radius: 10px;
        }

        .user:hover {
            background: var(--coffee);
        }

        /* inputs */
        input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border-radius: 10px;
            border: none;
        }

        /* button */
        button {
            padding: 10px;
            width: 100%;
            margin-top: 10px;
            background: var(--latte);
            color: var(--brown);
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        button:hover {
            background: var(--cream);
        }
    </style>
</head>

<body>

<!-- ✅ NAVBAR -->
<nav>
    <h1>CoffeeHouse</h1>

    <div class="links">
        <a href="main_page.php">Home</a>
        <a href="menu.php">Menu</a>
        <a href="map.php">Store Locator</a>
        <a href="Admin.php">Profile</a>

        <?php if ($is_logged_in): ?>
            <a href="logout.php">Sign Out</a>
        <?php else: ?>
            <a href="Sign_Up.html">Sign In</a>
        <?php endif; ?>

        <!-- ✅ FIXED CART ICON -->
        <a href="cart.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 fill="#4B2E1E" viewBox="0 0 24 24">
                <path d="M21 6H7.05L5.94 2.68A1 1 0 0 0 4.99 2h-3v2h2.28l3.54 10.63A2 2 0 0 0 9.71 16h7.59a2 2 0 0 0 1.87-1.3l2.76-7.35c.11-.31.07-.65-.11-.92A1 1 0 0 0 21 6m-3.69 8H9.72l-2-6h11.84zM10 18a2 2 0 1 0 0 4 2 2 0 1 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 1 0 0-4"/>
            </svg>
        </a>
    </div>
</nav>

<h1>Admin Dashboard</h1>

<div class="container">

    <!-- USERS -->
    <div class="panel">
        <h2>Users</h2>
        <div id="userList"></div>
    </div>

    <!-- USER DETAILS -->
    <div class="panel">
        <h2>User Details</h2>

        <input type="text" id="name" placeholder="Name">
        <input type="text" id="address" placeholder="Address">
        <input type="email" id="email" placeholder="Email">
        <input type="password" id="password" placeholder="Password">

        <button onclick="saveUser()">Save</button>
    <button onclick="resetPassword()">Reset Password</button>

        <h3>Orders</h3>
        <ul id="orders"></ul>
    </div>

</div>

<script>
let selectedEmail=null;

function displayUsers(){
fetch("get_users.php")
.then(r=>r.json())
.then(data=>{
    let list=document.getElementById("userList");
    list.innerHTML="";

    data.forEach(u=>{
        let d=document.createElement("div");
        d.innerText=u.full_name+" ("+u.email+")";
        d.onclick=()=>loadUser(u);
        list.appendChild(d);
    });
});
}

function loadUser(u){
selectedEmail=u.email;

name.value=u.full_name;
address.value=u.address;
email.value=u.email;
password.value=u.password;

fetch("get_orders.php?email="+u.email)
.then(r=>r.json())
.then(data=>{
    let list=document.getElementById("orders");
    list.innerHTML="";
    data.forEach(o=>{
        let li=document.createElement("li");
        li.innerText="Order "+o.orderid+" ₹"+o.total;
        list.appendChild(li);
    });
});
}

function saveUser(){
let fd=new FormData();
fd.append("email",selectedEmail);
fd.append("full_name",name.value);
fd.append("address",address.value);
fd.append("password",password.value);

fetch("update_user.php",{method:"POST",body:fd})
.then(r=>r.text())
.then(alert);
}

function resetPassword(){
fetch("reset_password.php?email="+selectedEmail)
.then(r=>r.text())
.then(alert);
}

displayUsers();
</script>

</body>
</html>