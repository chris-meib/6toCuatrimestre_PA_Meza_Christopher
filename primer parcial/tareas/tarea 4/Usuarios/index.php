<?php 
require_once "../config/connection.php";

$stmt = $conn->prepare(
    "SELECT u.*, r.nombre AS rol_nombre FROM usuarios u LEFT JOIN roles r ON u.id_rol = r.id"
);
$stmt->execute();
$result = $stmt->get_result();
$usuarios = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<?php include_once "../includes/header.php";  ?>
<div class="page-header">
    <h1><i class="fas fa-users"></i> Usuarios</h1>
    <a href="create.php" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Nuevo Usuario
    </a>
</div>
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['id'] ?></td>
                    <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                    <td><?= htmlspecialchars($usuario['apellido']) ?></td>
                    <td><?= htmlspecialchars($usuario['correo']) ?></td>
                    <td><?= htmlspecialchars($usuario['telefono']) ?></td>
                    <td><?= htmlspecialchars($usuario['rol_nombre'] ?? 'Sin rol') ?></td>
                    <td><?= isset($usuario['activo']) && $usuario['activo'] == 1 ? 'Activo' : 'Inactivo' ?></td>
                    <td>
                        <a href="edit.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="delete.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">
                            <i class="fas fa-trash"></i> Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include_once "../includes/footer.php";  ?>
