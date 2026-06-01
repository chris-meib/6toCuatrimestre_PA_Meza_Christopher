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

    $stmt = $conn->prepare("INSERT INTO prestamos (id_usuario, fecha_prestamo, fecha_devolucion) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id_usuario, $fecha_prestamo, $fecha_devolucion);

    if ($stmt->execute()) {
        $id_prestamo = $conn->insert_id;
        $stmt->close();

        $stmtDetalle = $conn->prepare("INSERT INTO detalle_prestamo (id_prestamo, id_libro, cantidad) VALUES (?, ?, 1)");
        $stmtDetalle->bind_param("ii", $id_prestamo, $id_libro);
        if ($stmtDetalle->execute()) {
            $stmtDetalle->close();
            $conn->close();
            header("Location: index.php?success=Préstamo creado correctamente.");
            exit();
        } else {
            $stmtDetalle->close();
            $conn->close();
            header("Location: create.php?error=Error al crear el detalle del préstamo.");
            exit();
        }
    } else {
        $stmt->close();
        $conn->close();
        header("Location: create.php?error=Error al crear el préstamo.");
        exit();
    }
}

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
    <h1><i class="fas fa-plus"></i> Nuevo Préstamo</h1>
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
    <form action="create.php" method="POST">
        <div class="form-group">
            <label>Usuario</label>
            <select name="id_usuario" required>
                <option value="">Seleccione un usuario</option>
                <?php while ($usuario = $resultUsuarios->fetch_assoc()): ?>
                    <option value="<?= $usuario['id'] ?>"><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Libro</label>
            <select name="id_libro" required>
                <option value="">Seleccione un libro</option>
                <?php while ($libro = $resultLibros->fetch_assoc()): ?>
                    <option value="<?= $libro['id'] ?>"><?= htmlspecialchars($libro['titulo']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Fecha de Préstamo</label>
            <input type="date" name="fecha_prestamo" required>
        </div>
        <div class="form-group">
            <label>Fecha de Devolución</label>
            <input type="date" name="fecha_devolucion" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Préstamo</button>
    </form>
</div>
<?php include_once "../includes/footer.php"; ?>
