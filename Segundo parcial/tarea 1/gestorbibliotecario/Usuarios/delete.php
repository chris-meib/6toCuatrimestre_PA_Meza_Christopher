<?php 
require_once "../config/connection.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: index.php?success=Usuario eliminado correctamente.");
        exit();
    } else {
        header("Location: index.php?error=Error al eliminar el usuario.");
        exit();
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php?error=ID de usuario no especificado.");
    exit();
}
?>

