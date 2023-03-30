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

require_once "./../config.php";

// Consulta sql para sacar las compras de la tabla orders, y sacar el nombre del usuario que realice la compra por el user_id (order por fecha)
$sql = "SELECT orders.id, orders.user_id, orders.product_id, orders.order_date, users.username, products.name FROM orders INNER JOIN users ON orders.user_id = users.id INNER JOIN products ON orders.product_id = products.id ORDER BY orders.order_date DESC";
$result = $mysqli->query($sql);

//Cerramos la conexion
$mysqli->close();

//Crear un PDF con una tabla con los datos de las compras usando FPDF
require('./../fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Reporte de compras');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'ID');
$pdf->Cell(40, 10, 'Usuario');
$pdf->Cell(40, 10, 'Producto');
$pdf->Cell(40, 10, 'Fecha');
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 12);

while ($row = $result->fetch_assoc()) {
  $pdf->Cell(40, 10, $row['id']);
  $pdf->Cell(40, 10, $row['username']);
  $pdf->Cell(40, 10, $row['name']);
  $pdf->Cell(40, 10, $row['order_date']);
  $pdf->Ln(10);
}

$pdf->Output();
