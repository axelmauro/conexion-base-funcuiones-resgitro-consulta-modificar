<?php
// Conectar a la base de datos usando la función mysqli_connect
$conexion = mysqli_connect('localhost', 'root', '', 'gestion_usuarios') or die('No se pudo conectar: ' . mysqli_error($conexion));

// datos del nuevo formulario para registro de un nuevo usuario, con los datos de nombre, correo y contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario registrar_usuario
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Insertar los datos en la base de datos usando una consulta 
    $insertarDatoSql = 'INSERT INTO usuarios (nombre, correo, contrasena, fecha_registro) VALUES (?, ?, ?, NOW())';
    $consultaInsertar = mysqli_prepare($conexion, $insertarDatoSql);
    mysqli_stmt_bind_param($consultaInsertar, 'sss', $nombre, $correo, $contrasena);

    if (mysqli_stmt_execute($consultaInsertar)) {
        echo 'Registro exitoso.';
    } else {
        echo 'Error al registrar el usuario: ' . mysqli_error($conexion);
    }
}
// Realizar una consulta SQL para obtener todos los registros de la tabla usuarios usando una consulta preparada
$baseMysql = 'SELECT * FROM usuarios';
$consultaBase = mysqli_prepare($conexion, $baseMysql) or die('Consulta fallida: ' . mysqli_error($conexion));
mysqli_stmt_execute($consultaBase);
$resultado = mysqli_stmt_get_result($consultaBase);

// Crear una tabla HTML con encabezados para cada campo
echo '<table border="1">';
echo '<tr>';
echo '<th>id_usuarios</th>';
echo '<th>nombre</th>';
echo '<th>correo</th>';
echo '<th>contrasena</th>';
echo '<th>fecha_registro</th>';
echo '</tr>';

// Obtener todo el resultado de la consulta en un solo array usando el método fetch_all
$filas = mysqli_fetch_all($resultado, MYSQLI_BOTH);

// Recorrer el array con un bucle foreach y mostrar cada campo como una celda de la tabla HTML
foreach ($filas as $fila) {
    echo '<tr>';
    echo '<td>' . $fila['id_usuarios'] . '</td>';
    echo '<td>' . $fila['nombre'] . '</td>';
    echo '<td>' . $fila['correo'] . '</td>';
    echo '<td>' . $fila['contrasena'] . '</td>';
    echo '<td>' . $fila['fecha_registro'] . '</td>';
    echo '</tr>';
}

echo '</table>';

// Cerrar la conexión cuando ya no se necesite
mysqli_close($conexion);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro Nuevo Usuario</title>
</head>
<body>
    <h1>Registro Nuevo Usuario</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required><br><br>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required><br><br>

        <input type="submit" value="Registrar">
    </form>
</body>
</html>
