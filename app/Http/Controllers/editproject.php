<?php
session_start();
require_once '../database/db.php';

// Instancia la clase Database y obtiene la conexión PDO
$db = new Database();
$pdo = $db->connect();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['projectId'])) {
    $projectId = $_POST['projectId'];
    $projectName = $_POST['projectName'];
    $projectOwner = $_POST['projectOwner'];
    $projectStatus = $_POST['projectStatus'];

    var_dump($projectId, $projectName, $projectOwner, $projectStatus);
    $sql = "UPDATE proyectos SET projectName = :projectName, projectOwner = :projectOwner, projectStatus = :projectStatus WHERE id_proyecto = :projectId";
$stmt = $pdo->prepare($sql);

// Ejecuta la declaración con los valores proporcionados y asigna el resultado a $result
$result = $stmt->execute([
    ':projectId' => $projectId,
    ':projectName' => $projectName,
    ':projectOwner' => $projectOwner,
    ':projectStatus' => $projectStatus
]);

// Verifica el resultado de la ejecución
if ($result) {
    echo "Registro actualizado con éxito.";
    // Redirige o realiza cualquier otra acción necesaria después de la actualización exitosa
} else {
    echo "Error al actualizar el registro.";
    // Maneja el caso de error en la actualización
}
    // Redirige a la página de proyectos o muestra un mensaje de confirmación
    // header("Location: ../view/Proyectos.php");
    exit();
}

