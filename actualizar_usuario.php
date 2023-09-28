<?php
// Conectar a la base de datos
$conexion = mysqli_connect('localhost', 'root', '', 'gestion_usuarios') or die('No se pudo conectar: ' . mysqli_error($conexion));

// Inicializar variables
$idUsuario = '';
$nuevoNombre = '';
$nuevoCorreo = '';
$mensaje = '';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ID de usuario del formulario
    $idUsuario = $_POST['id_usuario'];

    // Verificar si el ID de usuario existe en la base de datos
    $verificarQuery = "SELECT COUNT(*) as total FROM usuarios WHERE id_usuarios = ?";
    $stmt = mysqli_prepare($conexion, $verificarQuery);
    mysqli_stmt_bind_param($stmt, "i", $idUsuario);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $fila = mysqli_fetch_assoc($resultado);
    $totalUsuarios = $fila['total'];

    // Si el ID de usuario no existe, mostrar un mensaje de error
    if ($totalUsuarios === 0) {
        $mensaje = "El ID de usuario no existe.";
    } else {
        // Si el ID de usuario existe, obtener los datos del formulario
        $nuevoNombre = $_POST['nuevo_nombre'];
        $nuevoCorreo = $_POST['nuevo_correo'];

        // Validar que el correo no contenga espacios en blanco
        if (strpos($nuevoCorreo, ' ') !== false) {
            $mensaje = "El correo no puede contener espacios en blanco.";
        } else {
            // Crear una consulta SQL para actualizar el nombre y el correo del usuario
            $actualizarQuery = "UPDATE usuarios SET nombre = ?, correo = ? WHERE id_usuarios = ?";
            $stmt = mysqli_prepare($conexion, $actualizarQuery);

            // Vincular los parámetros
            mysqli_stmt_bind_param($stmt, "ssi", $nuevoNombre, $nuevoCorreo, $idUsuario);

            // Ejecutar la consulta
            if (mysqli_stmt_execute($stmt)) {
                $mensaje = "Usuario actualizado con éxito.";
            } else {
                $mensaje = "Error al actualizar el usuario: " . mysqli_error($conexion);
            }

            // Cerrar la consulta preparada
            mysqli_stmt_close($stmt);
        }
    }
}

// Cerrar la conexión cuando ya no se necesite
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Actualizar Datos de Usuario</title>
</head>
<body>
    <h1>Actualizar Datos de Usuario</h1>
    <form method="post" action="">
        <label for="id_usuario">ID de Usuario:</label>
        <input type="text" name="id_usuario" value="<?php echo $idUsuario; ?>"><br>
        <label for="nuevo_nombre">Nuevo Nombre:</label>
        <input type="text" name="nuevo_nombre" value="<?php echo $nuevoNombre; ?>"><br>
        <label for="nuevo_correo">Nuevo Correo:</label>
        <input type="text" name="nuevo_correo" value="<?php echo $nuevoCorreo; ?>"><br>
        <input type="submit" value="Actualizar">
    </form>
    <?php
    // Mostrar el mensaje de éxito o error
    if (!empty($mensaje)) {
        echo '<p>' . $mensaje . '</p>';
    }
    ?>
</body>
</html>
