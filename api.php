<?php
ini_set('display_errors', 0);
error_reporting(0);
require_once 'basedatos.php';

header('Content-Type: application/json; charset=UTF-8');

$accion = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $accion = isset($_GET['accion']) ? $_GET['accion'] : '';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true);
    $accion = isset($body['accion']) ? $body['accion'] : '';
}

$conn = conectar();

switch ($accion) {

    case 'listar':
        $res = $conn->query("SELECT * FROM tareas ORDER BY creada_en DESC");
        $tareas = $res->fetch_all(MYSQLI_ASSOC);
        echo json_encode(array('ok' => true, 'tareas' => $tareas));
        break;

    case 'agregar':
        $descripcion = $conn->real_escape_string($body['descripcion']);
        $conn->query("INSERT INTO tareas (descripcion) VALUES ('$descripcion')");
        $id = $conn->insert_id;
        $res = $conn->query("SELECT * FROM tareas WHERE id = $id");
        $tarea = $res->fetch_assoc();
        echo json_encode(array('ok' => true, 'tarea' => $tarea));
        break;

    case 'actualizar':
        $id = (int) $body['id'];
        $completada = (int) $body['completada'];
        $conn->query("UPDATE tareas SET completada = $completada WHERE id = $id");
        echo json_encode(array('ok' => true));
        break;

    case 'eliminar':
        $id = (int) $body['id'];
        $conn->query("DELETE FROM tareas WHERE id = $id");
        echo json_encode(array('ok' => true));
        break;

    default:
        echo json_encode(array('ok' => false, 'mensaje' => 'Accion no reconocida'));
        break;
}

$conn->close();

?>