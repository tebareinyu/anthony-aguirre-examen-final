<?php
require 'db.php';

// Obtén la URL y el método
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = explode('/', trim($_GET['q'], '/'));

switch ($requestMethod) {
    case 'GET':
        handleGet($requestUri);
        break;
    case 'POST':
        handlePost();
        break;
    case 'PUT':
        handlePut($requestUri);
        break;
    case 'DELETE':
        handleDelete($requestUri);
        break;
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Metodo no permitido']);
}

function handleGet($requestUri) {
    global $conn;
    
    if (count($requestUri) === 1 && $requestUri[0] === 'tasks') {
        $stmt = $conn->prepare("SELECT * FROM tasks");
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tasks);
    } elseif (count($requestUri) === 3 && $requestUri[0] === 'tasks') {
        $id = intval($requestUri[1]);
        $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($task);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'No se encuentra']);
    }
}

function handlePost() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)");
    $stmt->execute([$data['title'], $data['description'], $data['status'] ?? false]);
    
    echo json_encode(['message' => 'Task Creada puro power', 'id' => $conn->lastInsertId()]);
}

function handlePut($requestUri) {
    global $conn;
    $id = intval($requestUri[1]);
    $data = json_decode(file_get_contents("php://input"), true);
    
    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
    $stmt->execute([$data['title'], $data['description'], $data['status'], $id]);
    
    echo json_encode(['message' => 'Task Actualizada']);
}

function handleDelete($requestUri) {
    global $conn;
    $id = intval($requestUri[1]);
    
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    
    echo json_encode(['message' => 'Task Eliminada']);
}
?>