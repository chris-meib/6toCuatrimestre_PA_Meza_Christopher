<?php
require_once "../config/connection.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id'] ?? 0);
    $id_usuario = intval($_POST['id_usuario'] ?? 0);
    $id_libro = intval($_POST['id_libro'] ?? 0);
    $fecha_prestamo = $_POST['fecha_prestamo'] ?? '';
    $fecha_devolucion = $_POST['fecha_devolucion'] ?? '';

    if ($id <= 0 || $id_usuario <= 0 || $id_libro <= 0 || $fecha_prestamo === '' || $fecha_devolucion === '') {
        header("Location: edit.php?id={$id}&error=Todos los campos son obligatorios.");
        exit();
    }

    $stmt = $conn->prepare("UPDATE prestamos SET id_usuario = ?, fecha_prestamo = ?, fecha_devolucion = ? WHERE id = ?");
    $stmt->bind_param("issi", $id_usuario, $fecha_prestamo, $fecha_devolucion, $id);

    if (!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: edit.php?id={$id}&error=Error al actualizar el préstamo.");
        exit();
    }
    $stmt->close();

    $stmtExiste = $conn->prepare("SELECT COUNT(*) FROM detalle_prestamo WHERE id_prestamo = ?");
    $stmtExiste->bind_param("i", $id);
    $stmtExiste->execute();
    $stmtExiste->bind_result($detalleCount);
    $stmtExiste->fetch();
    $stmtExiste->close();

    if ($detalleCount > 0) {
        $stmtDetalle = $conn->prepare("UPDATE detalle_prestamo SET id_libro = ? WHERE id_prestamo = ?");
        $stmtDetalle->bind_param("ii", $id_libro, $id);
        $stmtDetalle->execute();
        $stmtDetalle->close();
    } else {
        $stmtInsertDetalle = $conn->prepare("INSERT INTO detalle_prestamo (id_prestamo, id_libro, cantidad) VALUES (?, ?, 1)");
        $stmtInsertDetalle->bind_param("ii", $id, $id_libro);
        $stmtInsertDetalle->execute();
        $stmtInsertDetalle->close();
    }

    $conn->close();
    header("Location: index.php?success=Préstamo actualizado correctamente.");
    exit();
} else {
    header("Location: index.php?error=Solicitud no válida.");
    exit();
}
?>