<?php
// Start the session
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}

$user_id = $_SESSION["id"];

include 'config.php';

//obtener el historial de compras del usuario de la tabla orders (sacar tambien el nombre del producto con la llave foranea de produc_id)
$sql = "SELECT orders.id, orders.order_date, products.name, products.price FROM orders INNER JOIN products ON orders.product_id = products.id WHERE user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historial de compras</title>
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
    <h1 class="mt-5">Historial de compras</h1>
    <table class="table mt-3">
      <thead>
        <tr>
          <th scope="col">Fecha</th>
          <th scope="col">Producto</th>
          <th scope="col">Precio</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order) : ?>
          <tr>
            <td><?php echo $order['order_date']; ?></td>
            <td><?php echo $order['name']; ?></td>
            <td><?php echo $order['price']; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
  </main>
</body>

</html>