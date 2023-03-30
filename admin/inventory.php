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

$nombre = $descripcion = $precio = $cantidad = "";
$nombre_err = $descripcion_err = $precio_err = $cantidad_err = "";

//Consulta sql para obtener los datos de la tabla productos
$sql = "SELECT * FROM products";

//Ejecutar la consulta
$result = $mysqli->query($sql);

//Cerramos la conexion
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous" />
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</head>

<body>
  <main class="container-fluid">
    <h1 class="h3 text-gray-800 mt-5">Productos</h1>
    <a href="./../admin.php">Volver al panel de admin</a>
    <div class="mb-4"></div>

    <!-- Mensajes de error -->
    <?php if (!empty($nombre_err)) : ?>
      <div class="alert alert-danger">
        <span class="help-block"><?php echo $nombre_err; ?></span>
      </div>
    <?php endif; ?>
    <?php if (!empty($descripcion_err)) : ?>
      <div class="alert alert-danger">
        <span class="help-block"><?php echo $descripcion_err; ?></span>
      </div>
    <?php endif; ?>
    <?php if (!empty($precio_err)) : ?>
      <div class="alert alert-danger">
        <span class="help-block"><?php echo $precio_err; ?></span>
      </div>
    <?php endif; ?>
    <?php if (!empty($cantidad_err)) : ?>
      <div class="alert alert-danger">
        <span class="help-block"><?php echo $cantidad_err; ?></span>
      </div>
    <?php endif; ?>

    <!-- Tabla de productos -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Lista de productos</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Cantidad</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                  <td><?php echo $row['name']; ?></td>
                  <td><?php echo $row['description']; ?></td>
                  <td><?php echo $row['price']; ?></td>
                  <td><?php echo $row['quantity']; ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</body>

</html>