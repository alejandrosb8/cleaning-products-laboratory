<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ya ha iniciado sesión, si es así, redirigirlo a la página de inicio
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
  header("location: index.php");
  exit;
}

// Incluir la conexión a la base de datos
require_once "config.php";

// Definir variables e inicializar con valores vacíos
$username = $password = "";
$username_err = $password_err = "";

// Procesamiento del formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Verificar si el nombre de usuario está vacío
  if (empty(trim($_POST["username"]))) {
    $username_err = "Por favor ingrese su nombre de usuario.";
  } else {
    $username = trim($_POST["username"]);
  }

  // Verificar si la contraseña está vacía
  if (empty(trim($_POST["password"]))) {
    $password_err = "Por favor ingrese su contraseña.";
  } else {
    $password = trim($_POST["password"]);
  }

  // Validar credenciales de inicio de sesión
  if (empty($username_err) && empty($password_err)) {
    // Preparar una declaración SELECT
    $sql = "SELECT id, username, password, role, balance FROM users WHERE username = ?";

    if ($stmt = $mysqli->prepare($sql)) {
      // Vincular variables a la declaración preparada como parámetros
      $stmt->bind_param("s", $param_username);

      // Establecer parámetros
      $param_username = $username;

      // Intentar ejecutar la declaración preparada
      if ($stmt->execute()) {
        // Almacenar el resultado
        $stmt->store_result();

        // Verificar si el nombre de usuario existe, si es así, verificar la contraseña
        if ($stmt->num_rows == 1) {
          // Vincular variables de resultado
          $stmt->bind_result($id, $username, $hashed_password, $role, $balance);
          if ($stmt->fetch()) {
            if (password_verify($password, $hashed_password)) {
              // Si la contraseña es correcta, iniciar una nueva sesión
              session_start();

              // Almacenar datos en variables de sesión
              $_SESSION["loggedin"] = true;
              $_SESSION["id"] = $id;
              $_SESSION["username"] = $username;
              $_SESSION["role"] = $role;
              $_SESSION["balance"] = $balance;

              if ($role == 'admin') {
                header("location: admin.php");
              } else {
                header("location: index.php");
              }
            } else {
              // Si la contraseña no es válida, mostrar un mensaje de error
              $password_err = "La contraseña que has ingresado no es válida.";
            }
          }
        } else {
          // Si el nombre de usuario no existe, mostrar un mensaje de error
          $username_err = "No se encontró ninguna cuenta con ese nombre de usuario.";
        }
      } else {
        echo "Oops! Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
      }

      // Cerrar la declaración preparada
      $stmt->close();
    }
  }

  // Cerrar la conexión a la base de datos
  $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="./assets/css/global.css">
</head>

<body>
  <main class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="card mb-3 shadow-lg" style="max-width: 640px;">
      <div class="row g-0">
        <div class="col-md-5">
          <div class="position-relative h-100">
            <img src="./assets/login-image.jpg" class="img-fluid rounded-start h-100 object-fit-cover login-image" alt="...">
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-light" style="background-color: rgba(0,0,0,0.5);">
              <h1 class="text-center fs-2 px-2 w-100">Laboratorio de productos de limpieza</h1>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="card-body">
            <h5 class="card-title mt-4">Iniciar sesión</h5>
            <form action="login.php" method="post">
              <div class="mb-3">
                <label for="username" class="form-label">Usuario</label>
                <input required type="text" class="form-control" id="username" name="username">
                <span class="text-danger"><?php echo $username_err; ?></span>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input required type="password" class="form-control" id="password" name="password">
                <span class="text-danger"><?php echo $password_err; ?></span>
              </div>
              <button type="submit" class="btn btn-primary w-100 mt-2">Ingresar</button>
            </form>
            <p class="mt-4 text-center">¿No tienes una cuenta? <a href="./signup.php">Regístrate</a></p>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>

</html>