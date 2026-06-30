<?php 
require_once "../config/connection.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $stmt->close();
} else {
    header("Location: index.php?error=ID de usuario no especificado.");
    exit();
}
?>
<?php include_once "../includes/header.php";  ?>
<div class="page-header">
    <h1><i class="fas fa-user-edit"></i> Editar Usuario</h1>
    <a href="index.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
<div class="card">
    <form action="update.php" method="POST">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

        <div class="form-group
">
            <label><i class="fas fa-user"></i> Nombre</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
        </div>
        <div class="form-group">
            <label><i class="fas fa-user"></i> Apellido</label>
            <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>
        </div>
        <div class="form-group">
            <label><i class="fas fa-envelope"></i> correo</label>
            <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
        </div>
        <div class="form-group">
            <label><i class="fas fa-lock"></i> Contraseña</label>
            <input type="password" name="password" placeholder="Dejar en blanco para mantener la contraseña actual">
        </div>
        <div class="form-group">
            <label><i class="fas fa-phone"></i> Teléfono</label>
            <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Guardar Cambios
        </button>
    </form>
</div>
<?php include_once "../includes/footer.php";  ?>
