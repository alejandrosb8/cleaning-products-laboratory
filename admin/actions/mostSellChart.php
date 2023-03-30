<?php

// Start the session
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}

// Check if user is admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
  header("location: index.php");
  exit;
}

require_once "./../../config.php";

// Consulta sql de los productos mas vendidos en orden descendente
$sql = "SELECT p.name, SUM(o.quantity) as total_sold FROM orders o JOIN products p ON o.product_id = p.id GROUP BY p.id ORDER BY total_sold DESC";
$result = $mysqli->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

//Cerramos la conexion
$mysqli->close();

header('Content-Type: application/json');
exit(json_encode($data));
