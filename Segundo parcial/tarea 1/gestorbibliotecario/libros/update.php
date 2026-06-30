<?php
require_once "../config/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id'] ?? 0);
    $titulo = $_POST['titulo'];
    $autor_id = $_POST['autor_id'] ?? null;
    $categoria_id = intval($_POST['categoria_id'] ?? 0);
    $anio_publicacion = $_POST['anio_publicacion'] ?? null;
    $isbn = $_POST['isbn'] ?? '';
    $editorial = $_POST['editorial'] ?? '';
    $cantidad = intval($_POST['cantidad'] ?? 1);
    $disponibles = intval($_POST['disponibles'] ?? $cantidad);

    if ($id <= 0) {
        header("Location: index.php?error=ID inválido");
        exit;
    }

    $stmt = $conn->prepare("UPDATE libros SET titulo = ?, isbn = ?, anio_publicacion = ?, editorial = ?, cantidad = ?, disponibles = ?, id_categoria = ? WHERE id = ?");
    $stmt->bind_param("sssiiiii", $titulo, $isbn, $anio_publicacion, $editorial, $cantidad, $disponibles, $categoria_id, $id);

    if ($stmt->execute()) {
        // Actualizar el autor
        if (!empty($autor_id)) {
            // Eliminar el autor anterior
            $stmt_delete = $conn->prepare("DELETE FROM libro_autor WHERE id_libro = ?");
            $stmt_delete->bind_param("i", $id);
            $stmt_delete->execute();
            $stmt_delete->close();

            // Insertar el nuevo autor
            $stmt2 = $conn->prepare("INSERT INTO libro_autor (id_libro, id_autor) VALUES (?, ?)");
            $stmt2->bind_param("ii", $id, $autor_id);
            $stmt2->execute();
            $stmt2->close();
        }
        header("Location: index.php?success=Libro actualizado correctamente.");
        exit();
    } else {
        header("Location: index.php?error=Error al actualizar el libro.");
        exit();
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php?error=Solicitud no válida.");
    exit();
}
?>