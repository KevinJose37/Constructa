<?php
session_start();
require_once '../database/db.php';

// Instancia la clase Database
$db = new Database();

// Obtiene la conexión PDO
$pdo = $db->connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $projectName = $_POST['projectName'];
    $projectOwner = $_POST['projectOwner'];
    $projectStatus = $_POST['projectStatus'];

    // Prepara la consulta SQL para insertar los datos en la base de datos
    $sql = "INSERT INTO proyectos (projectName, projectOwner, projectStatus ) VALUES (:projectName, :projectOwner, :projectStatus)";
    $stmt = $pdo->prepare($sql);

    // Ejecuta la consulta con los datos proporcionados
    $stmt->execute([
        ':projectName' => $projectName,
        ':projectOwner' => $projectOwner,
        ':projectStatus' => $projectStatus
    ]);

    // Redirige a la página con la tabla o a donde desees mostrar un mensaje de éxito
    header("Location: ../view/Proyectos.php");
    exit();
}
