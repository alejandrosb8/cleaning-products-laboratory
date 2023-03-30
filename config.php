<?php
$db_servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "laboratory";

// Create connection
$mysqli = new mysqli($db_servername, $db_username, $db_password, $db_name);

// Check connection
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}
