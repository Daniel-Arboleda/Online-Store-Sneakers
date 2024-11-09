<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sneakers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center; /* Centrar el contenido */
            height: 100vh; /* Altura completa de la ventana */
        }
        .login-form {
            width: 50%;
        }
    </style>
</head>
<body>
    <?php include 'menu_out.php'; ?>
    <div class="container login-container">
        <div class="login-form">
            <h2 class="mb-4">Iniciar Sesión</h2>
            <form method="POST" action="login_user.php">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required autocomplete="username">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                </div>
            </form>
            
            <div class="form-group">
                <a href="index.php" class="btn btn-secondary">Crear Cuenta</a>
            </div>
        </div>
    </div>
</body>
</html>
