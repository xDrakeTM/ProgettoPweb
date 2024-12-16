<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $conn = new mysqli("localhost", "root", "", "carinci_635710");
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    $timestamp_logout = date('Y-m-d H:i:s');
    $user_email = $_SESSION["user_email"];
    $user_tipo = $_SESSION["user_tipo"];

    if ($user_tipo != 'admin') {
        if ($user_tipo === 'utente') {
            $stmt = $conn->prepare("UPDATE utente SET timestamp_logout = ? WHERE email = ?");
        } 
        elseif ($user_tipo === 'personal_trainer') {
            $stmt = $conn->prepare("UPDATE personal_trainer SET timestamp_logout = ? WHERE email = ?");
        }
    
        $stmt->bind_param("ss", $timestamp_logout, $user_email);
        $stmt->execute();
    }

    $_SESSION = [];
    session_destroy();

    header("Location: ../php/login.php");
    exit();
?>