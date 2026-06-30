<?php include_once "../includes/header.php";
    require_once "../config/connection.php";

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

<div class="page-header">
    <h1><i class="fas fa-book-plus"></i> Nuevo Libro</h1>
    <a href="index.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <form action="save.php" method="POST">
        <div class="form-group">
            <label><i class="fas fa-heading"></i> Título</label>
            <input type="text" name="titulo" placeholder="Ej: Cien años de soledad" required>
        </div>

        <div class="form-group">
            <label><i class="fas fa-barcode"></i> ISBN</label>
            <input type="text" name="isbn" placeholder="Ej: 978-1234567890" required>
        </div>

        <div class="form-group">
            <label><i class="fas fa-user"></i> Autor</label>
            <select name="autor_id" required>
                <option value="">-- Seleccionar Autor --</option>
                <?php while ($autor = $result_autores->fetch_assoc()): ?>
                    <option value="<?= $autor['id'] ?>">
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
                    <option value="<?= $categoria['id'] ?>">
                        <?= htmlspecialchars($categoria['nombre']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label><i class="fas fa-calendar"></i> Año de Publicación</label>
            <input type="number" name="anio_publicacion" placeholder="Ej: 2024" min="1000" max="2099">
        </div>

        <div class="form-group">
            <label><i class="fas fa-building"></i> Editorial</label>
            <input type="text" name="editorial" placeholder="Ej: Penguin">
        </div>

        <div class="form-group">
            <label><i class="fas fa-boxes"></i> Cantidad</label>
            <input type="number" name="cantidad" value="1" min="1" required>
        </div>

        <div class="form-group">
            <label><i class="fas fa-check"></i> Disponibles</label>
            <input type="number" name="disponibles" value="1" min="0" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Guardar
            </button>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<?php include_once "../includes/footer.php"; ?>