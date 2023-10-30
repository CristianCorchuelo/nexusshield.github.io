<?php

session_start();

include 'conexion_be.php';

$correo = $_POST['correo']; 
$contrasena = $_POST['contrasena']; 

// Encripta la contraseña antes de usarla en la consulta
$contrasena = hash('sha512', $contrasena);

// Preparar la consulta
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE correo = ? AND contrasena = ?");

// Vincular los parámetros
$stmt->bind_param("ss", $correo, $contrasena);

// Ejecutar la consulta
$stmt->execute();

// Obtener los resultados
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['usuario'] = $correo; 
    header("Location: ../index/index.php");
    exit();
} else {
    echo '
    <script>
        alert("Usuario no existe, por favor verifique los datos introducidos");
        window.location = "../index.php";
    </script>'
    ;
    exit();
}

?>
