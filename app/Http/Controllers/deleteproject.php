<?php
session_start();
require_once '../database/db.php';

// Instancia la clase Database
$db = new Database();

// Obtiene la conexión PDO
$pdo = $db->connect();

if (isset($_GET['id'])) {
    $projectId = $_GET['id'];

    // Prepara la declaración SQL para eliminar el proyecto
    $sql = "DELETE FROM proyectos WHERE id_proyecto = :projectId";
    $stmt = $pdo->prepare($sql);

    // Ejecuta la declaración con el ID del proyecto
    $stmt->execute([':projectId' => $projectId]);

    // Redirige de nuevo a la página con la tabla
    header("Location: ../view/Proyectos.php");
    exit();
} else {
    // Si no hay un ID, redirige de vuelta a la página de proyectos o maneja el error como prefieras
    header("Location: ../view/Proyectos.php");
    exit();
}
