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
            $stmt = $conn->prepare("SELECT id, id_usuario, fecha_prestamo, fecha_devolucion, estado FROM prestamos WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $prestamo = $resultado->fetch_assoc();
                echo json_encode($prestamo);
            } else {
                echo json_encode(array("mensaje" => "Préstamo no encontrado"));
            }

            $stmt->close();
            $conn->close();

        } elseif (isset($_GET['id_usuario'])) {
            $id_usuario = intval($_GET['id_usuario']);
            $stmt = $conn->prepare("SELECT id, id_usuario, fecha_prestamo, fecha_devolucion, estado FROM prestamos WHERE id_usuario = ?");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $prestamos = array();

            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    $prestamos[] = $row;
                }

                echo json_encode($prestamos);
            } else {
                echo json_encode(array("mensaje" => "Préstamo no encontrado"));
            }

            $stmt->close();
            $conn->close();

        } elseif (isset($_GET['estado'])) {
            $estado = trim($_GET['estado']);
            $stmt = $conn->prepare("SELECT id, id_usuario, fecha_prestamo, fecha_devolucion, estado FROM prestamos WHERE estado LIKE ?");
            $estado_param = "%$estado%";
            $stmt->bind_param("s", $estado_param);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $prestamos = array();

            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    $prestamos[] = $row;
                }

                echo json_encode($prestamos);
            } else {
                echo json_encode(array("mensaje" => "Préstamo no encontrado"));
            }

            $stmt->close();
            $conn->close();

        } else {
            $stmt = $conn->prepare("SELECT id, id_usuario, fecha_prestamo, fecha_devolucion, estado FROM prestamos");
            $stmt->execute();
            $resultado = $stmt->get_result();
            $prestamos = array();

            while ($row = $resultado->fetch_assoc()) {
                $prestamos[] = $row;
            }

            echo json_encode($prestamos);

            $stmt->close();
            $conn->close();
        }
        break;

    default:
        break;
}
?>
