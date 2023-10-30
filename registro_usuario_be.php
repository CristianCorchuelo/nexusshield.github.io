<?php
include 'conexion_be.php';
?>

<input type="text" id="nombre_completo" onkeypress="return soloLetras(event)">

<?php
// Recuperar datos del formulario y limpiarlos
$nombre_completo = mysqli_real_escape_string($conexion, $_POST['nombre_completo']);
$correo = mysqli_real_escape_string($conexion, $_POST['correo']);
$usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
$contrasena = $_POST['contrasena']; // No escapes the password

// Verificar si el nombre contiene números
if (preg_match('/[0-9]/', $nombre_completo)) {
    echo '
    <script>
      alert("El nombre no puede contener números");
      window.location = "../index.php";
    </script>
    ';
    exit(); // Finaliza el script
}

// Verificar si el correo tiene el símbolo "@" (es una validación básica)
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo '
    <script>
      alert("Por favor, ingresa una dirección de correo válida");
      window.location = "../index.php";
    </script>
    ';
    exit(); // Finaliza el script
}

// Verificar que no se repita el correo en la base de datos
$verificar_correo = $conexion->prepare("SELECT correo FROM usuarios WHERE correo = ?");
$verificar_correo->bind_param("s", $correo);
$verificar_correo->execute();
$verificar_correo->store_result();

if ($verificar_correo->num_rows > 0) {
    echo '
    <script>
      alert("El correo ya está registrado, intenta con otro diferente");
      window.location = "../index.php";
    </script>
    ';
    exit(); // Finaliza el script
}

// Verificar que no se repita el usuario en la base de datos
$verificar_usuario = $conexion->prepare("SELECT usuario FROM usuarios WHERE usuario = ?");
$verificar_usuario->bind_param("s", $usuario);
$verificar_usuario->execute();
$verificar_usuario->store_result();

if ($verificar_usuario->num_rows > 0) {
    echo '
    <script>
      alert("El usuario ya está registrado, intenta con otro diferente");
      window.location = "../index.php";
    </script>
    ';
    exit(); // Finaliza el script
}

// Verificar criterios de contraseña
if (
    strlen($contrasena) >= 11 &&
    preg_match('/[a-z]/', $contrasena) &&      // Letra minúscula
    preg_match('/[A-Z]/', $contrasena) &&      // Letra mayúscula
    preg_match('/[0-9]/', $contrasena) &&      // Número
    preg_match('/[^a-zA-Z0-9]/', $contrasena)  // Carácter especial
) {
    // Verificar que no haya más de 3 caracteres consecutivos
    if (!preg_match('/([a-zA-Z0-9])\1{3,}/', $contrasena)) {
        // Contraseña cumple con los criterios, se puede guardar en la base de datos
        $contrasena = hash('sha512', $contrasena); // Encriptar contraseña

        $query = "INSERT INTO usuarios (nombre_completo, correo, usuario, contrasena) 
                    VALUES (?, ?, ?, ?)";

        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ssss", $nombre_completo, $correo, $usuario, $contrasena);
        
        if ($stmt->execute()) {
            echo '
            <script>
                alert("Usuario almacenado exitosamente");
                window.location = "../index.php";
            </script>
            ';
        } else {
            echo '
            <script>
                alert("Inténtalo nuevamente, usuario no almacenado");
                window.location = "../index.php";
            </script>
            ';
        }

        $stmt->close();
    } else {
        echo '<script>alert("La contraseña no puede tener más de 3 caracteres consecutivos."); window.location = "../index.php";</script>';
        exit();
    }
} else {
    echo '<script>alert("La contraseña no cumple con los criterios establecidos."); window.location = "../index.php";</script>';
    exit();
}

mysqli_close($conexion);
?>
