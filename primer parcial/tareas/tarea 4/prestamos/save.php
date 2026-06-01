<?php
require_once "../config/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = intval($_POST['id_usuario'] ?? 0);
    $id_libro = intval($_POST['id_libro'] ?? 0);
    $fecha_prestamo = $_POST['fecha_prestamo'] ?? '';
    $fecha_devolucion = $_POST['fecha_devolucion'] ?? '';

    if ($id_usuario <= 0 || $id_libro <= 0 || $fecha_prestamo === '' || $fecha_devolucion === '') {
        header("Location: create.php?error=Todos los campos son obligatorios.");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO prestamos (id_usuario, id_libro, fecha_prestamo, fecha_devolucion) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $id_usuario, $id_libro, $fecha_prestamo, $fecha_devolucion);

    if ($stmt->execute()) {
        header("Location: index.php?success=Préstamo creado correctamente.");
        exit();
    } else {
        header("Location: create.php?error=Error al crear el préstamo.");
        exit();
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php?error=Solicitud no válida.");
    exit();
}
?>
