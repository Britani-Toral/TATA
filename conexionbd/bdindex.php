<?php
session_start();
include '../conexion.php';

// Obtener los datos enviados por el formulario
$nombreUsuario = $_POST['nombreUsuario'];
$contrasenaUsuario = $_POST['contrasenaUsuario'];

// Preparar la consulta para validar el usuario y obtener su rol y nombre completo
$sql = "SELECT d.*, r.NombreRolUsuario 
        FROM docentes d 
        JOIN rolusuarios r ON d.IdRolUsuario = r.IdRolUsuario 
        WHERE d.UsuarioD = :nombreUsuario AND d.ContrasenaD = :contrasenaUsuario";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':nombreUsuario', $nombreUsuario);
$stmt->bindParam(':contrasenaUsuario', $contrasenaUsuario);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $docente = $stmt->fetch();
    $_SESSION['usuario'] = $nombreUsuario;
    $_SESSION['rol'] = $docente['NombreRolUsuario']; // Guardar el nombre del rol en la sesión
    $_SESSION['docente_id'] = $docente['IdDocente']; // Guardar el ID del docente en la sesión
    $_SESSION['nombre_docente'] = $docente['NombreD'] . ' ' . $docente['ApaternoD'] . ' ' . $docente['AmaternoD']; // Guardar el nombre completo en la sesión

    // Redirigir a la página de navegación solo si la autenticación es exitosa
    header("Location: /TATA/modulos/navegacion.php");
    exit(); // Asegura que no se ejecute más código después de la redirección
} else {
    // Si la autenticación falla, redirige de vuelta al inicio de sesión
    echo '
    <script>
    alert("Este usuario no existe");
    window.location = "/TATA/modulos/index.php";
    </script>';
    exit(); // Asegura que no se ejecute más código después de la redirección
}
?>
