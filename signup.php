<?php

$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $role = $_POST["role"];

  if (empty($username)) {
    $username_err = "Por favor, ingresa un nombre de usuario";
  }

  // Validate password field
  if (empty($password)) {
    $password_err = "Por favor, ingresa una contraseña";
  }

  // Incluir la conexión a la base de datos
  require_once "config.php";

  // Verificar si el nombre de usuario ya existe
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($mysqli, $sql);
  if (mysqli_num_rows($result) > 0) {
    $username_err = "El nombre de usuario ya existe";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user data into the database
    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')";

    if (mysqli_query($mysqli, $sql)) {
      echo "User created successfully";
      header("location: login.php");
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($mysqli);
    }
  }

  mysqli_close($mysqli);
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
    <div class="card mb-3 shadow-lg" style="max-width: 540px;">
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
            <h5 class="card-title mt-4">Regístrate</h5>
            <form action="signup.php" method="post">
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
              <div class="mb-3">
                <label for="role" class="form-label">Rol</label>
                <select class="form-select" id="role" name="role">
                  <option value="user">Usuario</option>
                  <option value="admin">Administrador</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary w-100 mt-2">Registrarse</button>
              <p class="mt-4 text-center">¿Ya tienes una cuenta? <a href="./login.php">Inicia sesión</a></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>

</html>