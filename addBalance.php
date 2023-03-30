<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}

// Incluir la conexión a la base de datos
require_once "config.php";

$amount = 0;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $amount = $_POST['amount'];
  $id = $_SESSION['id'];
  $balance = $_SESSION['balance'];
  $newBalance = $balance + $amount;

  $sql = "UPDATE users SET balance = ? WHERE id = ?";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("ii", $newBalance, $id);
  $stmt->execute();
  $stmt->close();

  $_SESSION['balance'] = $newBalance;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recarga</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

  <style>
    .centered-container {
      min-height: 100vh;
    }
  </style>
</head>

<body>
  <div class="container d-flex align-items-center justify-content-center centered-container">
    <div>
      <h1 class="text-center">Recarga exitosa</h1>
      <p>¡Tu recarga de $<?php echo $amount ?> ha sido exitosa!</p>
      <span class="text-center w-100 d-block mt-2">Ahora mismo tienes $<?php echo $_SESSION['balance'] ?></span>
      <a href="./index.php" class="btn btn-primary w-100 mt-4">Volver a inicio</a>
    </div>
  </div>
</body>

</html>