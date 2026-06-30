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
            $stmt = $conn->prepare("SELECT id, id_prestamo, id_libro, cantidad FROM detalle_prestamo WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $detalle = $resultado->fetch_assoc();
                echo json_encode($detalle);
            } else {
                echo json_encode(array("mensaje" => "Registro no encontrado"));
            }

            $stmt->close();
            $conn->close();

        } elseif (isset($_GET['id_prestamo'])) {
            $id_prestamo = intval($_GET['id_prestamo']);
            $stmt = $conn->prepare("SELECT id, id_prestamo, id_libro, cantidad FROM detalle_prestamo WHERE id_prestamo = ?");
            $stmt->bind_param("i", $id_prestamo);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $detalles = array();

            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    $detalles[] = $row;
                }

                echo json_encode($detalles);
            } else {
                echo json_encode(array("mensaje" => "Registro no encontrado"));
            }

            $stmt->close();
            $conn->close();

        } elseif (isset($_GET['id_libro'])) {
            $id_libro = intval($_GET['id_libro']);
            $stmt = $conn->prepare("SELECT id, id_prestamo, id_libro, cantidad FROM detalle_prestamo WHERE id_libro = ?");
            $stmt->bind_param("i", $id_libro);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $detalles = array();

            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    $detalles[] = $row;
                }

                echo json_encode($detalles);
            } else {
                echo json_encode(array("mensaje" => "Registro no encontrado"));
            }

            $stmt->close();
            $conn->close();

        } else {
            $stmt = $conn->prepare("SELECT id, id_prestamo, id_libro, cantidad FROM detalle_prestamo");
            $stmt->execute();
            $resultado = $stmt->get_result();
            $detalles = array();

            while ($row = $resultado->fetch_assoc()) {
                $detalles[] = $row;
            }

            echo json_encode($detalles);

            $stmt->close();
            $conn->close();
        }
        break;

    default:
        break;
}
?>