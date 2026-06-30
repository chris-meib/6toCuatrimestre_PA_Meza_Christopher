<?php
require_once "../config/connection.php";

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: index.php?error=ID de préstamo no especificado.");
    exit();
}

$stmt = $conn->prepare(
    "SELECT p.id_usuario, dp.id_libro, p.fecha_prestamo, p.fecha_devolucion " .
    "FROM prestamos p " .
    "LEFT JOIN detalle_prestamo dp ON dp.id_prestamo = p.id " .
    "WHERE p.id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    $prestamo = $result->fetch_assoc();
    $id_usuario = intval($prestamo['id_usuario']);
    $id_libro = intval($prestamo['id_libro']);
    $fecha_prestamo = $prestamo['fecha_prestamo'];
    $fecha_devolucion = $prestamo['fecha_devolucion'];
} else {
    $stmt->close();
    $conn->close();
    header("Location: index.php?error=Préstamo no encontrado.");
    exit();
}
$stmt->close();

$stmtUsuarios = $conn->prepare("SELECT id, nombre, apellido FROM usuarios ORDER BY nombre");
$stmtUsuarios->execute();
$resultUsuarios = $stmtUsuarios->get_result();
$stmtUsuarios->close();

$stmtLibros = $conn->prepare("SELECT id, titulo FROM libros ORDER BY titulo");
$stmtLibros->execute();
$resultLibros = $stmtLibros->get_result();
$stmtLibros->close();

$conn->close();
?>
<?php include_once "../includes/header.php"; ?>
<div class="page-header">
    <h1><i class="fas fa-edit"></i> Editar Préstamo</h1>
    <a href="index.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_GET['error']) ?>
    </div>
<?php endif; ?>
<div class="card">
    <form action="update.php" method="POST">
        <input type="hidden" name="id" value="<?= $id ?>">

        <div class="form-group">
            <label>Usuario</label>
            <select name="id_usuario" required>
                <option value="">Seleccione un usuario</option>
                <?php while ($usuario = $resultUsuarios->fetch_assoc()): ?>
                    <option value="<?= $usuario['id'] ?>" <?= $usuario['id'] == $id_usuario ? 'selected' : '' ?>>
                        <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Libro</label>
            <select name="id_libro" required>
                <option value="">Seleccione un libro</option>
                <?php while ($libro = $resultLibros->fetch_assoc()): ?>
                    <option value="<?= $libro['id'] ?>" <?= $libro['id'] == $id_libro ? 'selected' : '' ?>>
                        <?= htmlspecialchars($libro['titulo']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Fecha de Préstamo</label>
            <input type="date" name="fecha_prestamo" value="<?= htmlspecialchars($fecha_prestamo) ?>" required>
        </div>

        <div class="form-group">
            <label>Fecha de Devolución</label>
            <input type="date" name="fecha_devolucion" value="<?= htmlspecialchars($fecha_devolucion) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Préstamo</button>
    </form>
</div>
<?php include_once "../includes/footer.php"; ?>