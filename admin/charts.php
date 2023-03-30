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
<html>

<head>
  <title>Graficas</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous" />
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</head>

<body>
  <main class="container">
    <h1 class="mt-5">Grafica de los productos más vendidos</h1>
    <canvas id="chartMostSell"></canvas>
    <h1 class="mt-5">Grafica de los productos más fabricados</h1>
    <canvas id="chartMostProduction"></canvas>
    <br>
    <a href="./../admin.php">Regresar al panel de admin</a>
    <div class="mb-5"></div>
  </main>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    function createChart(data, label, id, values) {
      let labels = data.map(item => item.name);

      let ctx = document.getElementById(id).getContext('2d');
      let myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: label,
            data: values,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          }
        }
      });
    }

    $.get('./actions/mostSellChart.php', function(data) {
      const values = data.map(item => item.total_sold);
      createChart(data, 'Ventas', 'chartMostSell', values);
    });
    $.get('./actions/mostProductionChart.php', function(data) {
      const values = data.map(item => item.quantity);
      createChart(data, 'Veces producido', 'chartMostProduction', values);
    });
  </script>
</body>

</html>