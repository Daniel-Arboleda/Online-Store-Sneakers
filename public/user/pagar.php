<!-- pagar.php -->

<?php
require __DIR__ . '/../../config/conexion.php';

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['user_id'])) {
    die("ID de usuario no disponible en la sesión.");
}
$usuario_id = $_SESSION['user_id'];

$total = 0;
$codigo_descuento = isset($_POST['codigo_descuento']) ? $_POST['codigo_descuento'] : null;
$descuento_aplicado = 0;

// Calcular el total del carrito
$sql = "
    SELECT ci.precio, ci.cantidad 
    FROM carrito c
    INNER JOIN carrito_item ci ON c.id = ci.carrito_id
    WHERE c.usuario_id = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->bind_result($precio, $cantidad);

    while ($stmt->fetch()) {
        $total += $precio * $cantidad;
    }
    $stmt->close();
} else {
    die("Error en la preparación de la consulta SQL: " . $mysqli->error);
}

// Validar y aplicar el código de descuento si existe
if ($codigo_descuento) {
    $sql = "SELECT porcentaje_descuento FROM cupones WHERE codigo = ? AND estado = 'activo'";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $codigo_descuento);
    $stmt->execute();
    $stmt->bind_result($porcentaje_descuento);
    if ($stmt->fetch()) {
        $descuento_aplicado = ($porcentaje_descuento / 100) * $total;
        $total -= $descuento_aplicado;
    } else {
        $_SESSION['mensaje_error'] = "Código de descuento inválido o inactivo.";
    }
    $stmt->close();
}

// Iniciar transacción
$mysqli->begin_transaction();

try {
    // Obtener la dirección del usuario
    // $sql = "SELECT direccion FROM usuarios WHERE id = ?";
    // $stmt = $mysqli->prepare($sql);
    // $stmt->bind_param("i", $usuario_id);
    // $stmt->execute();
    // $stmt->bind_result($direccion_envio);
    // if (!$stmt->fetch() || !$direccion_envio) {
    //     $stmt->close();
    //     throw new Exception("No se encontró dirección para el usuario.");
    // }
    // $stmt->close();

    // Obtener la dirección del usuario mediante el email que es identificador unico del usurio en usuarios y pasandole el email de la sesion para que es un dato validado
    $sql = "SELECT direccion FROM usuarios WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $_SESSION['email']);
    $stmt->execute();
    $stmt->bind_result($direccion_envio);
    if (!$stmt->fetch() || !$direccion_envio) {
        $stmt->close();
        throw new Exception("No se encontró dirección para el usuario.");
    }
    $stmt->close();

    // Insertar la factura con dirección de envío
    $sql = "INSERT INTO facturas (usuario_id, total, direccion_envio) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ids", $usuario_id, $total, $direccion_envio);
    if (!$stmt->execute()) {
        throw new Exception("Error al crear la factura.");
    }
    $factura_id = $stmt->insert_id;
    $stmt->close();

    // Insertar el pago
    $estado_pago = 'Pendiente';
    $metodo_pago = 'Tarjeta de crédito';
    $sql = "INSERT INTO pagos (factura_id, usuario_id, total, metodo_pago, estado_pago, fecha_pago) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iisss", $factura_id, $usuario_id, $total, $metodo_pago, $estado_pago);
    if (!$stmt->execute()) {
        throw new Exception("Error al crear el pago.");
    }
    $stmt->close();

    // Simular pago exitoso
    $pago_exitoso = true; // Este valor debería depender de un proceso real de pago

    // Actualizar estado del pago
    $estado_pago = $pago_exitoso ? 'Exitoso' : 'Fallido';

    $sql = "UPDATE pagos SET estado_pago = ? WHERE factura_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("si", $estado_pago, $factura_id);
    if (!$stmt->execute()) {
        throw new Exception("Error al actualizar el estado del pago.");
    }
    $stmt->close();

    if ($estado_pago === 'Exitoso') {
        // Vaciar el carrito y sus items
        $sql = "DELETE FROM carrito_item WHERE carrito_id IN (SELECT id FROM carrito WHERE usuario_id = ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        if (!$stmt->execute()) {
            throw new Exception("Error al vaciar los ítems del carrito.");
        }
        $stmt->close();

        // Eliminar el carrito
        $sql = "DELETE FROM carrito WHERE usuario_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar el carrito.");
        }
        $stmt->close();

        $mysqli->commit();
        $_SESSION['mensaje_exito'] = "Pago realizado con éxito.";
        header('Location: dashboard.php');
        exit();
    } else {
        throw new Exception("El pago no pudo completarse.");
    }
} catch (Exception $e) {
    $mysqli->rollback();
    $_SESSION['mensaje_error'] = "Error durante el proceso de pago: " . $e->getMessage();
    header('Location: error_pagar.php'); // Redirigir a página de error o mostrar mensaje
    exit();
}

$mysqli->close();
?>
