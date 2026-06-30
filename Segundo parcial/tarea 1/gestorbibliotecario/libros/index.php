<?php
    require_once "../config/connection.php";
?>
<?php include_once "../includes/header.php"; ?>
<div class="page-header">
    <h1><i class="fas fa-book"></i> Libros</h1>
    <a href="create.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nuevo Libro
    </a>    
</div>
<?php if (isset($_GET["success"])): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?= htmlspecialchars($_GET["success"]) ?>
    </div>
<?php elseif (isset($_GET["error"])): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <?= htmlspecialchars($_GET["error"]) ?>
    </div>
<?php endif; ?>
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Categoría</th>
                <th>Año de Publicación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sql = "SELECT l.id, l.titulo, GROUP_CONCAT(CONCAT(a.nombre, ' ', a.apellido) SEPARATOR ', ') AS autor, c.nombre AS categoria, l.anio_publicacion " .
                       "FROM libros l " .
                       "LEFT JOIN categorias c ON l.id_categoria = c.id " .
                       "LEFT JOIN libro_autor la ON la.id_libro = l.id " .
                       "LEFT JOIN autores a ON la.id_autor = a.id " .
                       "GROUP BY l.id, l.titulo, c.nombre, l.anio_publicacion";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result && $result->num_rows > 0) {
                    $i = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $i++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['autor']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['categoria']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['anio_publicacion']) . "</td>";
                        echo "<td>";
                        echo "<a href=\"edit.php?id=" . $row['id'] . "\" class=\"btn btn-sm btn-primary\">Editar</a> ";
                        echo "<a href=\"delete.php?id=" . $row['id'] . "\" class=\"btn btn-sm btn-danger\">Eliminar</a>";
                        echo "</td>";
                        echo "</tr>\n";
                    }
                } else {
                    echo "<tr><td colspan=\"6\" class=\"text-center\">No hay libros registrados.</td></tr>";
                }
                $stmt->close();
                $conn->close();
            ?>
        </tbody>
    </table>
</div>
<?php include_once "../includes/footer.php"; ?>
