<?php
require_once "../config/connection.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php?error=Solicitud no válida.");
    exit();
}

$id = intval($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$password = $_POST['password'] ?? '';
$telefono = trim($_POST['telefono'] ?? '');
$activo = intval($_POST['activo'] ?? -1);
$id_rol = intval($_POST['id_rol'] ?? 0);

if ($id <= 0 || $nombre === '' || $apellido === '' || $correo === '' || $activo < 0 || $id_rol <= 0) {
    header("Location: index.php?error=Datos incompletos.");
    exit();
}


$stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ? AND id <> ?");
$stmt->bind_param("si", $correo, $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    $stmt->close();
    $conn->close();
    header("Location: edit.php?id={$id}&error=El correo ya está en uso por otro usuario.");
    exit();
}
$stmt->close();

$roleCheck = $conn->prepare("SELECT id FROM roles WHERE id = ?");
if (!$roleCheck) {
    $conn->close();
    header("Location: edit.php?id={$id}&error=Error al validar el rol.");
    exit();
}
$roleCheck->bind_param("i", $id_rol);
$roleCheck->execute();
$roleCheck->store_result();
if ($roleCheck->num_rows === 0) {
    $roleCheck->close();
    $conn->close();
    header("Location: edit.php?id={$id}&error=Rol inválido.");
    exit();
}
$roleCheck->close();

if ($password !== '') {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, correo = ?, password = ?, telefono = ?, id_rol = ?, activo = ? WHERE id = ?");
    $stmt->bind_param("ssssiiii", $nombre, $apellido, $correo, $password_hash, $telefono, $id_rol, $activo, $id);
} else {
    $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, correo = ?, telefono = ?, id_rol = ?, activo = ? WHERE id = ?");
    $stmt->bind_param("sssiiii", $nombre, $apellido, $correo, $telefono, $id_rol, $activo, $id);
}

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: index.php?success=Usuario actualizado correctamente.");
    exit();
} else {
    $error = urlencode("Error al actualizar el usuario.");
    $stmt->close();
    $conn->close();
    header("Location: edit.php?id={$id}&error={$error}");
    exit();
}
?>
