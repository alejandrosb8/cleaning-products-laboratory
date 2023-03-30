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
$sql = "SELECT name, quantity FROM products ORDER BY quantity DESC";
$result = $mysqli->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

//Cerramos la conexion
$mysqli->close();

header('Content-Type: application/json');
exit(json_encode($data));
