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
            $stmt = $conn->prepare("SELECT id, nombre, apellido, correo, telefono, id_rol, activo, fecha_registro FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $usuario = $resultado->fetch_assoc();
                echo json_encode($usuario);
            } else {
                echo json_encode(array("mensaje" => "Usuario no encontrado"));
            }

            $stmt->close();
            $conn->close();

        } elseif (isset($_GET['nombre'])) {
            $nombre = trim($_GET['nombre']);
            $stmt = $conn->prepare("SELECT id, nombre, apellido, correo, telefono, id_rol, activo, fecha_registro FROM usuarios WHERE nombre LIKE ?");
            $nombre_param = "%$nombre%";
            $stmt->bind_param("s", $nombre_param);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $usuarios = array();

            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    $usuarios[] = $row;
                }

                echo json_encode($usuarios);
            } else {
                echo json_encode(array("mensaje" => "Usuario no encontrado"));
            }

            $stmt->close();
            $conn->close();

        } elseif (isset($_GET['correo'])) {
            $correo = trim($_GET['correo']);
            $stmt = $conn->prepare("SELECT id, nombre, apellido, correo, telefono, id_rol, activo, fecha_registro FROM usuarios WHERE correo LIKE ?");
            $correo_param = "%$correo%";
            $stmt->bind_param("s", $correo_param);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $usuarios = array();

            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    $usuarios[] = $row;
                }

                echo json_encode($usuarios);
            } else {
                echo json_encode(array("mensaje" => "Usuario no encontrado"));
            }

            $stmt->close();
            $conn->close();

        } else {
            $stmt = $conn->prepare("SELECT id, nombre, apellido, correo, telefono, id_rol, activo, fecha_registro FROM usuarios");
            $stmt->execute();
            $resultado = $stmt->get_result();
            $usuarios = array();

            while ($row = $resultado->fetch_assoc()) {
                $usuarios[] = $row;
            }

            echo json_encode($usuarios);

            $stmt->close();
            $conn->close();
        }
        break;

    default:
        break;
}
?>