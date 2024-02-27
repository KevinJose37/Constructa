<?php
session_start(); // Inicia la sesión para poder acceder a $_SESSION

// Vacía todas las variables de sesión
$_SESSION = array();

// Si se desea destruir la sesión completamente, borra también la cookie de sesión.
// Nota: ¡Esto destruirá la sesión y no la información de la sesión!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruye la sesión.
session_destroy();

// Redirige al usuario a la página de inicio de sesión
header("Location: ../view/LoginConstructa.php"); // Asegúrate de cambiar 'login.php' por la ruta real a tu página de inicio de sesión
exit();

