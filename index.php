<?php
// Start the session
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}

$user_id = $_SESSION["id"];
$balance = 0;

include 'config.php';

//obtener el balance del usuario
$sql = "SELECT balance FROM users WHERE id = $user_id";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
$balance = $row['balance'];
$_SESSION['balance'] = $balance;

$sql = "SELECT * FROM products";
$result = $mysqli->query($sql);

$products = array();

if ($result->num_rows > 0) {
  // Almacena cada fila en el array de productos
  while ($row = $result->fetch_assoc()) {
    $products[] = $row;
  }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous" />
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <div class="bg-primary-subtle p-2 rounded">
        <span class="navbar-text me-2"><?php echo $_SESSION['username']; ?></span>
        <span id="current_balance" class="navbar-text me-2">$<?php echo $_SESSION['balance']; ?></span>
      </div>
      <?php if ($_SESSION['role'] == 'admin') : ?>
        <a class="btn btn-outline-primary mx-3" href="./admin.php">Ir al panel de administrador</a>
      <?php endif; ?>
      <div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="./index.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./balance.php">Recarga</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./userHistory.php">Historial</a>
          </li>
        </ul>
        <a class="btn btn-outline-danger" href="./logout.php">Logout</a>
      </div>
    </div>
  </nav>
  <main class="container">
    <h1 class="mt-5">Compra nuestros productos de limpieza</h1>
    <div class="row mt-3">
      <?php foreach ($products as $product) : ?>
        <div class="col-sm-4 my-3">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><?php echo $product["name"]; ?></h5>
              <p class="card-text"><?php echo $product["description"]; ?></p>
              <p class="card-text"><strong>Precio:</strong> $<?php echo $product["price"]; ?></p>
              <button class="btn btn-primary buy-button w-100" data-product-id="<?php echo $product["id"]; ?>">Comprar</button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Estado de compra</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Respuesta -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
<script>
  $(".buy-button").on("click", function() {
    // Obtiene el ID del producto a partir del atributo de datos del botón
    const productId = $(this).data("product-id");

    // Envía una solicitud AJAX al servidor para procesar la compra del producto
    $.post("buy.php", {
      product_id: productId
    }, function(res) {
      //formatear respuesta a json
      const data = JSON.parse(res);
      $(".modal-body").text(data.response);
      $("#myModal").modal("show");
      $("#current_balance").text("$" + data.balance);
    });
  });
</script>

</html>