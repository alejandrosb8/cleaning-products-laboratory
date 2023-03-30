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

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous" />
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</head>

<body>
  <main class="container">
    <h1 class="mt-5">Admin Panel</h1>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="./admin/inventory.php">Ver inventario</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./admin/production.php">Ver producción</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./admin/addProduction.php">Agregar productos y producción</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./admin/reports.php" target="_blank">Ver reporte de compras en PDF</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./admin/charts.php">Ver graficas</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./index.php">Entrar como usuario</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./logout.php">Cerrar sesión</a>
      </li>
    </ul>
  </main>
</body>

</html>