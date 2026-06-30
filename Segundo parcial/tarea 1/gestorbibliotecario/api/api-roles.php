<?php
require_once('../config/connection.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $stmt = $conn->prepare("SELECT id, nombre FROM roles WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $rol = $resultado->fetch_assoc();
                echo json_encode($rol);
            } else {
                echo json_encode(array("mensaje" => "Rol no encontrado"));
            }

            $stmt->close();
            $conn->close();
        } else {
            $stmt = $conn->prepare("SELECT id, nombre FROM roles");
            $stmt->execute();
            $resultado = $stmt->get_result();
            $roles = array();

            while ($row = $resultado->fetch_assoc()) {
                $roles[] = $row;
            }

            echo json_encode($roles);

            $stmt->close();
            $conn->close();
        }
        break;

    default:
        break;
}
?>