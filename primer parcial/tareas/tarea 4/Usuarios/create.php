<?php
require_once "../config/connection.php";

$roles = [];
$rolesStmt = $conn->prepare("SELECT id, nombre FROM roles ORDER BY nombre");
if ($rolesStmt) {
    $rolesStmt->execute();
    $rolesResult = $rolesStmt->get_result();
    if ($rolesResult) {
        $roles = $rolesResult->fetch_all(MYSQLI_ASSOC);
    }
    $rolesStmt->close();
}
?>
<?php include_once "../includes/header.php"; ?>
<div class="page-header">
    <h1><i class="fas fa-user-plus"></i> Nuevo Usuario</h1>
    <a href="index.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
<div class="card">
    <form action="save.php" method="POST">
        <div class="form-group">
            <label><i class="fas fa-user"></i> Nombre</label>
            <input type="text" name="nombre" placeholder="Ej: Juan" required>
        </div>

        <div class="form-group">
            <label><i class="fas fa-user"></i> Apellido</label>
            <input type="text" name="apellido" placeholder="Ej: Pérez" required>
        </div>
        <div class="form-group">
            <label><i class="fas fa-envelope"></i> Correo</label>
            <input type="email" name="correo" placeholder="Ej: juan@ejemplo.com" required>
        </div>
        <div class="form-group">
            <label><i class="fas fa-lock"></i> Contraseña</label>
            <input type="password" name="password" placeholder="Ej: ********" required>
        </div>
        <div class="form-group">
            <label><i class="fas fa-phone"></i> Teléfono</label>
            <input type="text" name="telefono" placeholder="Ej: 9810000000" required>
        </div>
        <div class="form-group">
            <label><i class="fas fa-toggle-on"></i> Estado</label>
            <select name="activo" required>
                <option value="">Seleccione estado</option>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <div class="form-group">
            <label><i class="fas fa-user-tag"></i> Rol</label>
            <select name="id_rol" required>
                <option value="">Seleccione un rol</option>
                <?php foreach ($roles as $rol): ?>
                    <option value="<?= $rol['id'] ?>"><?= htmlspecialchars($rol['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Guardar Usuario
        </button>
    </form>
</div>
<?php include_once "../includes/footer.php";  ?>


