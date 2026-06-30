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
            $stmt = $conn->prepare("SELECT id, nombre, apellido, nacionalidad, fecha_nacimiento FROM autores WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $autor = $resultado->fetch_assoc();
                echo json_encode($autor);
            } else {
                echo json_encode(array("mensaje" => "Autor no encontrado"));
            }

            $stmt->close();
            $conn->close();

        } elseif (isset($_GET['nombre'])) {
            $nombre = trim($_GET['nombre']);
            $stmt = $conn->prepare("SELECT id, nombre, apellido, nacionalidad, fecha_nacimiento FROM autores WHERE nombre LIKE ?");
            $nombre_param = "%$nombre%";
            $stmt->bind_param("s", $nombre_param);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $autores = array();

            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    $autores[] = $row;
                }

                echo json_encode($autores);
            } else {
                echo json_encode(array("mensaje" => "Autor no encontrado"));
            }

            $stmt->close();
            $conn->close();

        } elseif (isset($_GET['nacionalidad'])) {
            $nacionalidad = trim($_GET['nacionalidad']);
            $stmt = $conn->prepare("SELECT id, nombre, apellido, nacionalidad, fecha_nacimiento FROM autores WHERE nacionalidad LIKE ?");
            $nacionalidad_param = "%$nacionalidad%";
            $stmt->bind_param("s", $nacionalidad_param);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $autores = array();

            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    $autores[] = $row;
                }

                echo json_encode($autores);
            } else {
                echo json_encode(array("mensaje" => "Autor no encontrado"));
            }

            $stmt->close();
            $conn->close();

        } else {
            $stmt = $conn->prepare("SELECT id, nombre, apellido, nacionalidad, fecha_nacimiento FROM autores");
            $stmt->execute();
            $resultado = $stmt->get_result();
            $autores = array();

            while ($row = $resultado->fetch_assoc()) {
                $autores[] = $row;
            }

            echo json_encode($autores);

            $stmt->close();
            $conn->close();
        }
        break;

    default:
        break;
}
?>