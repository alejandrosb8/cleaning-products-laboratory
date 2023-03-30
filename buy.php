<?php

// Obtiene el ID del producto a partir de los datos enviados por el cliente
$product_id = $_POST["product_id"];

// Inicia una sesión para acceder a las variables de sesión
session_start();

// Obtiene el IDy el saldo del usuario de la sesión
$user_id = $_SESSION["id"];
$balance = $_SESSION["balance"];

//variable de mensaje de respuesta
$response = "";

// Incluye el archivo de configuración de la base de datos
include "config.php";

//sql para obtener el precio y cantidad del producto
$sql = "SELECT price, quantity FROM products WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($price, $quantity);
$stmt->fetch();
$stmt->close();

if ($quantity <= 0) {
  //devolver un array con el mensaje de error y el saldo actual
  $response = array("response" => "Producto agotado", "balance" => $balance);
  exit(json_encode($response));
}

if ($balance < $price) {
  //devolver un array con el mensaje de error y el saldo actual
  $response = array("response" => "Saldo insuficiente", "balance" => $balance);
  exit(json_encode($response));
}

// Prepara una declaración SQL para insertar una nueva compra
$sql = "INSERT INTO orders (user_id, product_id, quantity, order_date) VALUES (?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);
$fecha = date("Y-m-d H:i:s");
$quantity_default = 1;
$stmt->bind_param("iiis", $user_id, $product_id, $quantity_default, $fecha);
$stmt->execute();
$stmt->close();

// Prepara una declaración SQL para actualizar el saldo del usuario
$sql = "UPDATE users SET balance = balance - ? WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("di", $price, $user_id);
$stmt->execute();
$stmt->close();

// Prepara una declaración SQL para obtener el saldo actual del usuario
$sql = "SELECT balance FROM users WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($balance);
$stmt->fetch();
$stmt->close();

//actualizar seccion
$_SESSION["balance"] = $balance;

// Prepara una declaración SQL para actualizar la cantidad del producto
$sql = "UPDATE products SET quantity = quantity - 1 WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->close();

$mysqli->close();

//devolver un array con el mensaje de éxito y el saldo actual
$response = array("response" => "Compra realizada con éxito", "balance" => $balance);
exit(json_encode($response));
