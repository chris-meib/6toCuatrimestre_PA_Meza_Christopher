<?php
require_once('../config/connection.php');


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$method = $_SERVER['REQUEST_METHOD'];


switch ($method) {

    case 'GET': 

        
        if (isset($_GET['titulo'])) {

            $titulo = trim($_GET['titulo']);

            $stmt = $conn->prepare("SELECT id, titulo, anio_publicacion FROM libros WHERE titulo LIKE ?");
            $titulo_param = "%$titulo%";

            $stmt->bind_param("s", $titulo_param);
            $stmt->execute();

            $resultado = $stmt->get_result();
            $libros = array();

            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    $libros[] = $row;
                }

                echo json_encode($libros);
            } else {
                echo json_encode(array("mensaje" => "Libro no encontrado"));
            }

            $stmt->close();
            $conn->close();
        }

      
        elseif (isset($_GET['anio'])) {

            $anio = intval($_GET['anio']);

            $stmt = $conn->prepare("SELECT id, titulo, anio_publicacion FROM libros WHERE anio_publicacion = ?");
            $stmt->bind_param("i", $anio);
            $stmt->execute();

            $resultado = $stmt->get_result();
            $libros = array();

            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    $libros[] = $row;
                }

                echo json_encode($libros);
            } else {
                echo json_encode(array("mensaje" => "No se encontraron libros con ese año"));
            }

            $stmt->close();
            $conn->close();
        }

        
        else {

            $stmt = $conn->prepare("SELECT id, titulo, anio_publicacion FROM libros");
            $stmt->execute();

            $resultado = $stmt->get_result();
            $libros = array();

            while ($row = $resultado->fetch_assoc()) {
                $libros[] = $row;
            }

            echo json_encode($libros);

            $stmt->close();
            $conn->close();
        }

        break;
   
    case 'POST':

    $data = json_decode(file_get_contents("php://input"), true);

    $titulo = trim($data['titulo'] ?? "");
    $anio_publicacion = intval($data['anio_publicacion'] ?? 0);
    $id_categoria = intval($data['id_categoria'] ?? 0);

    if (!empty($titulo) && $anio_publicacion > 0 && $id_categoria > 0) {

        $stmt = $conn->prepare("INSERT INTO libros (titulo, anio_publicacion, id_categoria) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $titulo, $anio_publicacion, $id_categoria);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array("mensaje" => "Libro creado exitosamente"));
        } else {
            http_response_code(500);
            echo json_encode(array("mensaje" => "Error al crear el libro: " . $stmt->error));
        }

        $stmt->close();
        $conn->close();

    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "El título, año de publicación e id_categoria son obligatorios"));
    }

         break;
 

    case 'PUT':
    if(isset($_GET['id'])){
        $id = intval($_GET['id']);
        $data = json_decode(file_get_contents("php://input"), true);

        $titulo = trim($data['titulo'] ?? "");
        $isbn = trim($data['isbn'] ?? "");
        $anio_publicacion = intval($data['anio_publicacion'] ?? 0);
        $editorial = trim($data['editorial'] ?? "");
        $cantidad = intval($data['cantidad'] ?? 0);
        $disponibles = intval($data['disponibles'] ?? 0);
        $id_categoria = intval($data['id_categoria'] ?? 0);

        if(!empty($titulo) && !empty($isbn) && $anio_publicacion > 0 && !empty($editorial) && $cantidad > 0 && $disponibles >= 0 && $id_categoria > 0){
            $stmt = $conn->prepare("UPDATE libros SET titulo = ?, isbn = ?, anio_publicacion = ?, editorial = ?, cantidad = ?, disponibles = ?, id_categoria = ? WHERE id = ?");
            $stmt->bind_param("ssisiiii", $titulo, $isbn, $anio_publicacion, $editorial, $cantidad, $disponibles, $id_categoria, $id);

            if($stmt->execute()){
                http_response_code(200);
                echo json_encode(array("mensaje" => "Libro actualizado exitosamente"));
            }else{
                http_response_code(500);
                echo json_encode(array("mensaje" => "Error al actualizar el libro " . $stmt->error));
            }
        }else{
            http_response_code(400);
            echo json_encode(array("mensaje" => "Todos los campos son obligatorios para actualizar el libro"));
        }
    }
    break;

case 'PATCH':
    if(isset($_GET['id'])){
        $id = intval($_GET['id']);
        $data = json_decode(file_get_contents("php://input"), true);

        $titulo = trim($data['titulo'] ?? "");
        $isbn = trim($data['isbn'] ?? "");
        $anio_publicacion = trim($data['anio_publicacion'] ?? "");
        $editorial = trim($data['editorial'] ?? "");
        $cantidad = trim($data['cantidad'] ?? "");
        $disponibles = trim($data['disponibles'] ?? "");
        $id_categoria = trim($data['id_categoria'] ?? "");

        if(!empty($titulo) || !empty($isbn) || !empty($anio_publicacion) || !empty($editorial) || !empty($cantidad) || !empty($disponibles) || !empty($id_categoria)){
            $stmt = $conn->prepare("UPDATE libros SET titulo = COALESCE(NULLIF(?, ''), titulo), isbn = COALESCE(NULLIF(?, ''), isbn), anio_publicacion = COALESCE(NULLIF(?, ''), anio_publicacion), editorial = COALESCE(NULLIF(?, ''), editorial), cantidad = COALESCE(NULLIF(?, ''), cantidad), disponibles = COALESCE(NULLIF(?, ''), disponibles), id_categoria = COALESCE(NULLIF(?, ''), id_categoria) WHERE id = ?");
            $stmt->bind_param("sssssssi", $titulo, $isbn, $anio_publicacion, $editorial, $cantidad, $disponibles, $id_categoria, $id);

            if($stmt->execute()){
                http_response_code(200);
                echo json_encode(array("mensaje" => "Libro actualizado exitosamente"));
            }else{
                http_response_code(500);
                echo json_encode(array("mensaje" => "Error al actualizar el libro " . $stmt->error));
            }
        }else{
            http_response_code(400);
            echo json_encode(array("mensaje" => "Al menos un campo es obligatorio para actualizar el libro"));
        }
    }
    break;

    case 'DELETE':
    $data = json_decode(file_get_contents("php://input"), true);
    
        break;

    default:
        break;
}
?>