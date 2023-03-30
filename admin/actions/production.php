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

//get the data from the form
$product_id = $_POST['id'];
$quantity = $_POST['quantity'];

// Consulta sql para insertar los datos
$sql = "UPDATE products SET quantity = quantity + $quantity WHERE id = $product_id";
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$stmt->close();

// Quitar producto de la tabla de produccion
$sql = "DELETE FROM production WHERE product_id = $product_id";
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$stmt->close();

$mysqli->close();

header("location: production.php");
exit;
