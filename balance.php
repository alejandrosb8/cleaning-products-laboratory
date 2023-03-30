<?php
// Start the session
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
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
  <div class="container max-w-50 d-flex align-items-center justify-content-center centered-container">
    <div class="shadow p-4 rounded">
      <h1 class="text-center">¡Haz tu recarga ahora!</h1>
      <p>¡Como parte de una promoción especial por ser el proyecto final de la materia, podrás recargar todo el dinero que quieras gratis! (Aviso legal: no, esto no es lavado de dinero).</p>
      <span class="text-center w-100 d-block mt-2">Ahora mismo tienes $<?php echo $_SESSION['balance'] ?></span>
      <form action="./addBalance.php" method="POST" class="mt-4">
        <div class="mb-3">
          <label for="amount" class="form-label">Cantidad a recargar</label>
          <input type="number" class="form-control" id="amount" name="amount" placeholder="0" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Recargar</button>
    </div>
  </div>
</body>

</html>