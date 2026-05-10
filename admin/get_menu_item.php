<?php
include("connect.php");

if(isset($_GET['id'])){

    $id = (int)$_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM menuitems WHERE mid=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($row = $result->fetch_assoc()){
        echo json_encode($row);
    }

    $stmt->close();
}
?>