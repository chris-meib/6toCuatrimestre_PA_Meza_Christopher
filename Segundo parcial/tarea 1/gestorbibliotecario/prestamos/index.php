<?php
require_once "../config/connection.php";

$stmt = $conn->prepare(
    "SELECT p.id, u.nombre AS nombre_usuario, MAX(l.titulo) AS titulo_libro, p.fecha_prestamo, p.fecha_devolucion " .
    "FROM prestamos p " .
    "JOIN usuarios u ON p.id_usuario = u.id " .
    "JOIN detalle_prestamo dp ON dp.id_prestamo = p.id " .
    "JOIN libros l ON dp.id_libro = l.id " .
    "GROUP BY p.id, u.nombre, p.fecha_prestamo, p.fecha_devolucion"
);
$stmt->execute();
$result = $stmt->get_result();
$prestamos = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();


?>
<?php include_once "../includes/header.php";  ?>
<div class="page-header">
    <h1><i class="fas fa-hand-holding-heart"></i> Préstamos</h1>
    <a href="create.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nuevo Préstamo
    </a>
</div>
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Libro</th>
                <th>Fecha de Préstamo</th>
                <th>Fecha de Devolución</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prestamos as $prestamo): ?>
                    <tr>
                        <td><?= $prestamo['id'] ?></td>
                        <td><?= htmlspecialchars($prestamo['nombre_usuario']) ?></td>
                        <td><?= htmlspecialchars($prestamo['titulo_libro']) ?></td>
                        <td><?= htmlspecialchars($prestamo['fecha_prestamo']) ?></td>
                        <td><?= htmlspecialchars($prestamo['fecha_devolucion']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $prestamo['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="delete.php?id=<?= $prestamo['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este préstamo?');">
                                <i class="fas fa-trash"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
</div>
<?php include_once "../includes/footer.php";  ?>


