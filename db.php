<?php
// db.php

$host = 'localhost';  // Cambia si tu base de datos está en otro servidor
$db = 'todo_db';
$user = 'root';       // Cambia el usuario de la base de datos
$pass = '';           // Cambia la contraseña si es necesario

// Establecer la conexión con la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
    die();
}