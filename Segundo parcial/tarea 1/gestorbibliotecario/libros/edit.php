<?php
    require_once "../config/connection.php";

    $id = intval($_GET["id"] ?? 0);

    if ($id <= 0) {
        header("Location: index.php?error=" . urlencode("ID inválido"));
        exit;
    }

    // Obtener el libro
    $stmt = $conn->prepare("SELECT * FROM libros WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        header("Location: index.php?error=" . urlencode("Libro no encontrado"));
        exit;
    }
    $libro = $result->fetch_assoc();
    $stmt->close();

    // Obtener el autor del libro
    $stmt_libro_autor = $conn->prepare("SELECT id_autor FROM libro_autor WHERE id_libro = ? LIMIT 1");
    $stmt_libro_autor->bind_param("i", $id);
    $stmt_libro_autor->execute();
    $result_libro_autor = $stmt_libro_autor->get_result();
    $libro_autor = $result_libro_autor->fetch_assoc();
    $autor_id = $libro_autor ? $libro_autor['id_autor'] : 0;
    $stmt_libro_autor->close();

    // Obtener autores y categorías para los selects
    $stmt_autores = $conn->prepare("SELECT id, nombre, apellido FROM autores ORDER BY nombre");
    $stmt_autores->execute();
    $result_autores = $stmt_autores->get_result();

    $stmt_categorias = $conn->prepare("SELECT id, nombre FROM categorias ORDER BY nombre");
    $stmt_categorias->execute();
    $result_categorias = $stmt_categorias->get_result();

    $stmt_autores->close();
    $stmt_categorias->close();
    $conn->close();

?>
<?php include_once "../includes/header.php";  ?>

<div class="page-header">
    <h1><i class="fas fa-book-edit"></i> Editar Libro</h1>
    <a href="index.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <form action="update.php" method="POST">
        <input type="hidden" name="id" value="<?= $libro['id'] ?>">

        <div class="form-group">
            <label><i class="fas fa-heading"></i> Título</label>
            <input type="text" name="titulo" value="<?= htmlspecialchars($libro['titulo']) ?>" required>
        </div>

        <div class="form-group">
            <label><i class="fas fa-barcode"></i> ISBN</label>
            <input type="text" name="isbn" value="<?= htmlspecialchars($libro['isbn']) ?>" required>
        </div>

        <div class="form-group">
            <label><i class="fas fa-user"></i> Autor</label>
            <select name="autor_id" required>
                <option value="">-- Seleccionar Autor --</option>
                <?php while ($autor = $result_autores->fetch_assoc()): ?>
                    <option value="<?= $autor['id'] ?>" <?= $autor['id'] == $autor_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($autor['nombre'] . ' ' . $autor['apellido']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label><i class="fas fa-tag"></i> Categoría</label>
            <select name="categoria_id" required>
                <option value="">-- Seleccionar Categoría --</option>
                <?php while ($categoria = $result_categorias->fetch_assoc()): ?>
                    <option value="<?= $categoria['id'] ?>" <?= $categoria['id'] == $libro['id_categoria'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria['nombre']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label><i class="fas fa-calendar"></i> Año de Publicación</label>
            <input type="number" name="anio_publicacion" value="<?= $libro['anio_publicacion'] ?>" min="1000" max="2099">
        </div>

        <div class="form-group">
            <label><i class="fas fa-building"></i> Editorial</label>
            <input type="text" name="editorial" value="<?= htmlspecialchars($libro['editorial']) ?>">
        </div>

        <div class="form-group">
            <label><i class="fas fa-boxes"></i> Cantidad</label>
            <input type="number" name="cantidad" value="<?= $libro['cantidad'] ?>" min="1" required>
        </div>

        <div class="form-group">
            <label><i class="fas fa-check"></i> Disponibles</label>
            <input type="number" name="disponibles" value="<?= $libro['disponibles'] ?>" min="0" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Actualizar
            </button>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<?php include_once "../includes/footer.php"; ?>
