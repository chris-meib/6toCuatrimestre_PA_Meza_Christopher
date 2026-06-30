<?php 
require_once "../config/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $autor_id = $_POST['autor_id'] ?? null;
    $categoria_id = intval($_POST['categoria_id'] ?? 0);
    $anio_publicacion = $_POST['anio_publicacion'] ?? null;
    $isbn = $_POST['isbn'] ?? '';
    $editorial = $_POST['editorial'] ?? '';
    $cantidad = intval($_POST['cantidad'] ?? 1);
    $disponibles = intval($_POST['disponibles'] ?? $cantidad);

    $stmt = $conn->prepare("INSERT INTO libros (titulo, isbn, anio_publicacion, editorial, cantidad, disponibles, id_categoria) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisii", $titulo, $isbn, $anio_publicacion, $editorial, $cantidad, $disponibles, $categoria_id);

    if ($stmt->execute()) {
        $new_book_id = $conn->insert_id;
        if (!empty($autor_id)) {
            $stmt2 = $conn->prepare("INSERT INTO libro_autor (id_libro, id_autor) VALUES (?, ?)");
            $stmt2->bind_param("ii", $new_book_id, $autor_id);
            $stmt2->execute();
            $stmt2->close();
        }
        header("Location: index.php?success=Libro creado correctamente.");
        exit();
    } else {
        header("Location: index.php?error=Error al crear el libro.");
        exit();
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php?error=Solicitud no válida.");
    exit();
}
?>
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
                $stmt = $conn->prepare("SELECT l.id, l.titulo, a.nombre AS autor, c.nombre AS categoria, l.anio_publicacion FROM libros l JOIN autores a ON l.autor_id = a.id JOIN categorias c ON l.categoria_id = c.id");
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
            ?>
            