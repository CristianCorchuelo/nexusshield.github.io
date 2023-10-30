<?php
session_start();

// Destruir la sesión
session_destroy();

// Redirigir al índice
header("Location: ../index.php");
exit();
?>
