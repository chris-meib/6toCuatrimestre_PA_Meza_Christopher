<?php
require_once "../config/connection.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';
    $telefono = trim($_POST['telefono'] ?? '');
    $activo = intval($_POST['activo'] ?? -1);
    $id_rol = intval($_POST['id_rol'] ?? 0);

    if ($nombre === '' || $apellido === '' || $correo === '' || $password === '' || $telefono === '' || $activo < 0 || $id_rol <= 0) {
        header("Location: create.php?error=Todos los campos son obligatorios.");
        exit();
    }

    $roleCheck = $conn->prepare("SELECT id FROM roles WHERE id = ?");
    if (!$roleCheck) {
        $conn->close();
        header("Location: create.php?error=Error al validar el rol.");
        exit();
    }
    $roleCheck->bind_param("i", $id_rol);
    $roleCheck->execute();
    $roleCheck->store_result();
    if ($roleCheck->num_rows === 0) {
        $roleCheck->close();
        $conn->close();
        header("Location: create.php?error=Rol inválido.");
        exit();
    }
    $roleCheck->close();

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, correo, password, telefono, activo, id_rol) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssii", $nombre, $apellido, $correo, $passwordHash, $telefono, $activo, $id_rol);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: index.php?success=Usuario creado correctamente.");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: create.php?error=Error al crear el usuario.");
        exit();
    }
} else {
    header("Location: index.php?error=Solicitud no válida.");
    exit();
}
?>
