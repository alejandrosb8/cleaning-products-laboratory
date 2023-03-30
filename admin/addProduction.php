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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require_once "./../config.php";

  //get the data from the form
  $name = $_POST['name'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  $quantity_default = 0;

  // Consulta sql para insertar los datos
  $sql = "INSERT INTO products (name, description, price, quantity) VALUES ('$name', '$description', $price, $quantity_default)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  $stmt->close();

  //consulta sql para insertar datos en produccion
  $sql = "INSERT INTO production (product_id, quantity) VALUES (LAST_INSERT_ID(), $quantity)";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  $stmt->close();

  $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agregar productos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous" />
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</head>

<body>
  <div class="container mt-5">
    <h1 class="h3 text-gray-800">Agrega un producto nuevo</h1>
    <a href="./../admin.php">Volver al panel de admin</a>
    <div class="mb-4"></div>
    <p>Esto agregara nuevos productos a producción, la cantidad es la cantidad la cual se producirá, este formulario no agrega un producto a su inventario, sino a su producción, para finalizar la produccíon y agregarlo al inventario debe ir a la respectiva ventana de producción.</p>
    <form action="addProduction.php" method="post">
      <div class="form-group">
        <label for="name">Nombre del producto</label>
        <input type="text" name="name" id="name" class="form-control" required>
      </div>
      <div class="form-group mt-2">
        <label for="description">Descripción</label>
        <input type="text" name="description" id="description" class="form-control" required>
      </div>
      <div class="form-group mt-2">
        <label for="price">Precio</label>
        <input type="number" name="price" id="price" class="form-control" required>
      </div>
      <div class="form-group mt-2">
        <label for="quantity">Cantidad</label>
        <input type="number" name="quantity" id="quantity" class="form-control" required>
      </div>
      <input type="submit" value="Agregar" class="btn btn-primary mt-3 w-100">
    </form>
  </div>
</body>

</html>