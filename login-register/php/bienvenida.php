<?php

  session_start();

  if (!isset($_SESSION['usuario'])) {
    echo '
      <script>
        alert("Por favor, debes iniciar sesión");
        window.location = "../index.php";
      </script>
      ';
    session_destroy(); // Esto destruirá la sesión si el usuario no está iniciado

    //  Redirigir al índice
    header("Location: ../index.php");
  }
?>
    
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenidos a NexusShield</title>
</head>
<body>
  <h1>Bienvenidos a NexusShield</h1>
  <a href="cerrar_sesion.php">cerrar sesion</a>
  
</body>
</html>
