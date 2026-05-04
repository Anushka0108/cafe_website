<?php
include("connect.php");
header('Content-Type: application/json');
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if (empty($q)) {
    echo json_encode([]);
    exit;
}
$q = $conn->real_escape_string($q);
$sql = "SELECT itemname, price, category FROM menuitems WHERE LOWER(itemname) LIKE LOWER('%$q%')";
$result = $conn->query($sql);
$items = [];
$imageMap = [
    'coffee' => 'assets/coffeeimg.png',
    'cold' => 'assets/coldcoffee.png',
    'bakery' => 'assets/bakery.png',
    'sandwich' => 'assets/sandwich1.png',
    'pizza' => 'assets/pizza1.png'
];
while ($row = $result->fetch_assoc()) {
    $row['image'] = $imageMap[$row['category']] ?? 'assets/coffeeimg.png';
    $items[] = $row;
}
echo json_encode($items);
?>

