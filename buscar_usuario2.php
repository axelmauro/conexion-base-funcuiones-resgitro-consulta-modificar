<!DOCTYPE html>
<html>

<head>
    <title>Buscar Usuario por Correo</title>
</head>

<body>
    <h1>Buscar Usuario por Correo</h1>
    <form action="buscar_usuario2.php" method="post">
        <label for="correo">Ingrese el correo electrónico del usuario:</label>
        <input type="email" id="correo" name="correo" required>
        <input type="submit" value="Buscar">
    </form>
    <?php
    // Verificar si se ha enviado el formulario y si 'correo' está definido en $_POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo'])) {
        // Conectar a la base de datos usando la función mysqli_connect
        $conexion = mysqli_connect('localhost', 'root', '', 'gestion_usuarios') or die('No se pudo conectar: ' . mysqli_error($conexion));

        // Obtener el correo electrónico enviado por el formulario usando la variable $_POST
        $correo = $_POST['correo'];

        // Realizar una consulta SQL para obtener el registro de la tabla usuarios que coincida con el correo electrónico usando una consulta preparada
        $baseMysql = 'SELECT nombre, fecha_registro FROM usuarios WHERE correo = ?';
        $consultaUsuario = mysqli_prepare($conexion, $baseMysql) or die('Consulta fallida: ' . mysqli_error($conexion));
        mysqli_stmt_bind_param($consultaUsuario, 's', $correo);
        mysqli_stmt_execute($consultaUsuario);
        $resultado = mysqli_stmt_get_result($consultaUsuario);

        // Verificar si se encontró algún registro usando la función mysqli_num_rows
        if (mysqli_num_rows($resultado) > 0) {
            // Obtener el registro encontrado usando la función mysqli_fetch_assoc
            $fila = mysqli_fetch_assoc($resultado);
            // Mostrar el nombre y la fecha de registro del usuario encontrado
            echo 'El usuario con el correo ' . $correo . ' se llama ' . $fila['nombre'] . ' y se registró el ' . $fila['fecha_registro'] . '.';
        } else {
            // Mostrar un mensaje indicando que no se encontró ningún usuario con el correo indicado
            echo 'No se encontró ningún usuario con el correo ' . $correo . '.';
        }

        // Cerrar la conexión cuando ya no se necesite
        mysqli_close($conexion);
    }
    ?>
</body>

</html>