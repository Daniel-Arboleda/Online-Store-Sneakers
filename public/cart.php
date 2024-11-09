<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.html'); // Redirigir al formulario de login si no está autenticado
    exit();
}

// Incluir el archivo de conexión a la base de datos
// require 'conexion.php';
require __DIR__ . '../config/conexion.php';


// Obtener el ID del usuario de la sesión
$usuario_id = $_SESSION['user_id']; // Asegúrate de usar el ID de usuario correcto aquí

// Consultar los productos en el carrito del usuario
$sql = "
    SELECT ci.id AS item_id, p.nombre, p.descripcion, p.precio, ci.cantidad, p.imagen, p.id AS producto_id
    FROM carrito c
    INNER JOIN carrito_item ci ON c.id = ci.carrito_id
    INNER JOIN productos p ON ci.producto_id = p.id
    WHERE c.usuario_id = ?
";

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    die("Error en la preparación de la consulta SQL: " . $mysqli->error);
}

// Guardar los resultados en una variable separada para renderizarlos después
$productos_carrito = $result->fetch_all(MYSQLI_ASSOC);

// Calcular el total del carrito
$total_carrito = 0; // Variable para calcular el total del carrito
foreach ($productos_carrito as $producto) {
    $total_carrito += $producto['precio'] * $producto['cantidad']; // Sumar el precio por la cantidad
}

// Verificar si se ha enviado un código de descuento
$total_con_descuento = $total_carrito; // Inicializar con el total sin descuento
$descuento = 0;

if (isset($_POST['codigo_descuento'])) {
    $codigo_descuento = $_POST['codigo_descuento'];

    // Consultar la base de datos para verificar si el código es válido
    $sql = "SELECT * FROM cupones WHERE codigo = ? AND activo = 1 AND CURDATE() BETWEEN fecha_inicio AND fecha_fin";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $codigo_descuento);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // El cupón es válido, obtener los datos del cupón
            $cupon = $result->fetch_assoc();
            $tipo_descuento = $cupon['tipo_descuento'];
            $valor_descuento = $cupon['valor'];
            $productos_aplicables = $cupon['productos_aplicables']; // productos aplicables (si es relevante)

            // Verificar si el cupón es aplicable a los productos del carrito
            if (!empty($productos_aplicables)) {
                $productos_aplicables = explode(',', $productos_aplicables);
                $aplicable = false;

                foreach ($productos_carrito as $producto) {
                    if (in_array($producto['producto_id'], $productos_aplicables)) {
                        $aplicable = true;
                        break;
                    }
                }

                if (!$aplicable) {
                    echo "Este cupón no es válido para los productos en tu carrito.";
                    exit();
                }
            }

            // Calcular el descuento
            if ($tipo_descuento === 'porcentaje') {
                $descuento = ($total_carrito * $valor_descuento) / 100;
            } elseif ($tipo_descuento === 'monto_fijo') {
                $descuento = $valor_descuento;
            }

            // Restar el descuento al total
            $total_con_descuento = $total_carrito - $descuento;
            echo "¡Descuento Aplicado! Has ahorrado: $".number_format($descuento, 2);
        } else {
            echo "El código de descuento no es válido o ha expirado.";
        }
    } else {
        echo "Error al verificar el código de descuento.";
    }
} 

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container mt-5">
        <h2>Tu Carrito de Compras</h2>
        <!-- Tabla para mostrar los productos en el carrito -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Imagen</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($productos_carrito) > 0): ?>
                    <?php foreach ($productos_carrito as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['item_id']); ?></td>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($producto['precio']); ?></td>
                            <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                            <td>
                                <?php if ($producto['imagen']): ?>
                                    <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen del producto" style="max-width: 100px;">
                                <?php else: ?>
                                    No disponible
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Formulario para eliminar un producto del carrito -->
                                <form action="eliminar_item.php" method="post" style="display:inline;">
                                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($producto['item_id']); ?>">
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No hay productos en el carrito.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Formulario de código de descuento -->
        <form action="" method="post">
            <div class="form-group">
                <label for="codigo_descuento">Código de Descuento</label>
                <input type="text" id="codigo_descuento" name="codigo_descuento" class="form-control" placeholder="Introduce tu código de descuento">
                <button type="submit" class="btn btn-primary mt-2">Aplicar Descuento</button>
            </div>
        </form>

        <!-- Mostrar el total del carrito -->
        <h4>Total: $<?php echo number_format($total_carrito, 2); ?></h4>

        <!-- Mostrar el descuento y el total con descuento si se aplicó -->
        <?php if (isset($total_con_descuento)): ?>
            <div class="alert alert-success mt-3">
                ¡Descuento Aplicado! Has ahorrado: $<?php echo number_format($descuento, 2); ?>
                <br>
                Total después del descuento: $<?php echo number_format($total_con_descuento, 2); ?>
            </div>
        <?php endif; ?>

        <!-- Botón para pagar -->
        <form action="pagar.php" method="post">
            <button type="submit" class="btn btn-success mt-3">Proceder al Pago</button>
        </form>
        <!-- Botón Redirigir a la Tienda-->
        <a href="tienda.php" class="btn btn-secondary btn-block mt-2">
            <i class="fas fa-store"></i> Ir a la Tienda
        </a>
</div>
</body>
</html>
