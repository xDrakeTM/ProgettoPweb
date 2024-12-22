<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if ($_SESSION["user_tipo"] == "utente") {
        header("Location: php/homeUtente.php");
    }
    elseif ($_SESSION["user_tipo"] == "personal_trainer") {
        header("Location: php/homePT.php");
    }
    else {
        header("Location: php/login.php");
    }
    exit();
?>