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

$sql = "SELECT p.name, pr.quantity, pr.id FROM products p INNER JOIN production pr ON p.id = pr.product_id";
$result = $mysqli->query($sql);

//Cerramos la conexion
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Producción</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous" />
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</head>

<body>
  <main class="container-fluid">
    <h1 class="h3 text-gray-800 mt-5">Productos en producción</h1>
    <a href="./../admin.php">Volver al panel de admin</a>
    <div class="mb-4"></div>

    <!-- Mensajes de error -->
    <?php if (!empty($nombre_err)) : ?>
      <div class="alert alert-danger">
        <span class="help-block"><?php echo $nombre_err; ?></span>
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
        <h6 class="m-0 font-weight-bold text-primary">Lista de productos en producción</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Cantidad en producción</th>
                <th>Terminar producción</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                  <td class="col-4"><?php echo $row["name"]; ?></td>
                  <td class="col-4"><?php echo $row["quantity"]; ?></td>
                  <td class="col-3">
                    <button class="btn btn-secondary w-100 finishProduct" data-id="<?php echo $row["id"]; ?>" data-quantity="<?php echo $row["quantity"]; ?>">Terminar producto</button>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</body>

<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
<script>
  //post request to finishProduct.php with jquery
  $(document).ready(function() {
    $(".finishProduct").click(function() {
      var id = $(this).data("id");
      var quantity = $(this).data("quantity");
      $.ajax({
        url: "./actions/production.php",
        type: "POST",
        data: {
          id: id,
          quantity: quantity
        },
        success: function(data) {
          location.reload();
        }
      });
    });
  });
</script>

</html>