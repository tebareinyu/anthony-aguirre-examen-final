<?php
$host = 'localhost'; // Cambia si es necesario
$dbname = 'todo_db'; // Cambia por tu base de datos
$username = 'root'; // Cambia por tu usuario de base de datos
$password = ''; // Cambia por tu contraseña

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>