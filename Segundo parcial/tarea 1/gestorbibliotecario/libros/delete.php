<?php
require_once "../config/connection.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Eliminar las referencias en libro_autor
    $stmt_delete_autor = $conn->prepare("DELETE FROM libro_autor WHERE id_libro = ?");
    $stmt_delete_autor->bind_param("i", $id);
    $stmt_delete_autor->execute();
    $stmt_delete_autor->close();
    
    // Eliminar el libro
    $stmt = $conn->prepare("DELETE FROM libros WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: index.php?success=Libro eliminado correctamente.");
        exit();
    } else {
        header("Location: index.php?error=Error al eliminar el libro.");
        exit();
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php?error=ID de libro no especificado.");
    exit();
}
?>
