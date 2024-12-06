<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Consulta categorías y géneros desde la base de datos
require __DIR__ . '/../config/conexion.php';

$query_categoria = "SELECT * FROM categorias";
$result_categoria = $mysqli->query($query_categoria);
$categorias = $result_categoria->fetch_all(MYSQLI_ASSOC);

$query_genero = "SELECT * FROM generos";
$result_genero = $mysqli->query($query_genero);
$generos = $result_genero->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
    <!-- Vinculación de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-warning text-dark">
    <?php include 'menu_admin.php'; ?>

    <div class="container my-4">
        <h1 class="text-center mb-4">Crear Nuevo Producto</h1>

        <!-- Formulario para agregar un nuevo producto -->
        <form action="products/producto.php" method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea class="form-control" name="descripcion" id="descripcion" required></textarea>
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio:</label>
                <input type="number" class="form-control" name="precio" id="precio" step="0.01" required>
            </div>

            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad:</label>
                <input type="number" class="form-control" name="cantidad" id="cantidad" required>
            </div>

            <div class="mb-3">
                <label for="categoria_id" class="form-label">Categoría:</label>
                <select class="form-select" name="categoria_id" id="categoria_id" required>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="genero_id" class="form-label">Género:</label>
                <select class="form-select" name="genero_id" id="genero_id" required>
                    <option value="">-- Seleccionar Género --</option>
                    <?php foreach ($generos as $genero): ?>
                        <option value="<?php echo $genero['id']; ?>"><?php echo $genero['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <input type="file" class="form-control" name="imagen" id="imagen" required>
            </div>

            <button type="submit" class="btn btn-outline-dark">Guardar Producto</button>
        </form>

        <!-- Botón para redirigir a la página de Ver Stock -->
        <a href="ver_stock.php" class="btn btn-outline-secondary mt-3">Ver Stock</a>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
