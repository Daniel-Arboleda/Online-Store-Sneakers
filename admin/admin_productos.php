<!-- admin_productos.php -->

<?php
session_start();
require __DIR__ . '/../config/conexion.php';
// Verificar si el usuario está autenticado y tiene el rol de administrador
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['rol_id'] !== 1) {
    header('Location: login.php');
    exit();
}

// Obtener todos los productos de la base de datos, incluyendo el nombre de la categoría y el género
$sql = "SELECT productos.id, productos.nombre, productos.descripcion, productos.precio, productos.cantidad, productos.imagen, 
            categorias.nombre AS categoria_nombre, generos.nombre AS genero_nombre
        FROM productos
        LEFT JOIN categorias ON productos.categoria_id = categorias.id
        LEFT JOIN generos ON productos.genero_id = generos.id";
$result = $mysqli->query($sql);

// Obtener todas las categorías para el formulario
$sql_categorias = "SELECT id, nombre FROM categorias";
$categorias = $mysqli->query($sql_categorias);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <?php include 'menu_admin.php'; ?>

    <div class="container mt-5">
        <h2>Gestión de Productos</h2>
        
        <!-- Botón para abrir el formulario de agregar producto -->
        <button class="btn btn-primary mb-3" id="openAddProductForm">Agregar Nuevo Producto</button>
        
        <!-- Tabla de productos -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Imagen</th>
                    <th>Categoría</th>
                    <th>Género</th> <!-- Nueva columna para el género -->
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($row['precio']); ?></td>
                        <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                        <td>
                            <?php if ($row['imagen'] && file_exists(__DIR__ . '/../uploads/' . $row['imagen'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($row['imagen']); ?>" alt="Imagen" style="max-width: 100px;">
                            <?php else: ?>
                                No disponible
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['categoria_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['genero_nombre']); ?></td> <!-- Mostrar el nombre del género -->
                        <td>
                            <button class="btn btn-warning edit-product" data-id="<?php echo $row['id']; ?>">Editar</button>
                            <form action="eliminar_producto.php" method="post" style="display:inline;">
                                <input type="hidden" name="producto_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Formulario para agregar/editar producto -->
        <div id="productForm" style="display: none;">
            <h3 id="formTitle">Agregar Producto</h3>
            <form id="productFormInner" enctype="multipart/form-data">
                <input type="hidden" name="id" id="productId">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="precio">Precio:</label>
                    <input type="number" id="precio" name="precio" class="form-control" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" id="cantidad" name="cantidad" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" id="imagen" name="imagen" class="form-control-file" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="categoria_id">Categoría:</label>
                    <select id="categoria_id" name="categoria_id" class="form-control" required>
                        <?php while ($categoria = $categorias->fetch_assoc()): ?>
                            <option value="<?php echo $categoria['id']; ?>"><?php echo htmlspecialchars($categoria['nombre']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="genero">Género:</label>
                    <select id="genero" name="genero" class="form-control" required>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Unisex">Unisex</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Guardar</button>
                <button type="button" class="btn btn-secondary" id="cancelForm">Cancelar</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Mostrar el formulario de agregar producto
            $('#openAddProductForm').click(function() {
                $('#formTitle').text('Agregar Producto');
                $('#productFormInner').trigger("reset");
                $('#productForm').show();
            });

            // Cancelar y ocultar el formulario
            $('#cancelForm').click(function() {
                $('#productForm').hide();
            });

            // Lógica para manejar la edición del producto
            $('.edit-product').click(function() {
                var productId = $(this).data('id');
                $.ajax({
                    url: 'obtener_producto.php',  // Crear este archivo para obtener datos del producto
                    method: 'POST',
                    data: { id: productId },
                    success: function(data) {
                        var producto = JSON.parse(data);
                        $('#productId').val(producto.id);
                        $('#nombre').val(producto.nombre);
                        $('#descripcion').val(producto.descripcion);
                        $('#precio').val(producto.precio);
                        $('#cantidad').val(producto.cantidad);
                        $('#categoria_id').val(producto.categoria_id);
                        $('#genero').val(producto.genero);  // Mostrar el género en el formulario
                        $('#formTitle').text('Editar Producto');
                        $('#productForm').show();
                    }
                });
            });

            // Enviar el formulario de agregar/editar producto usando AJAX
            $('#productFormInner').submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: 'guardar_producto.php',  // Crear este archivo para guardar o actualizar el producto
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert(response);
                        location.reload();  // Recargar la página para ver los cambios
                    }
                });
            });
        });
    </script>
</body>
</html>
