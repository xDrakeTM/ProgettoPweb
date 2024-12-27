<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION["user_id"])) {
        if ($_SESSION["user_tipo"] === "utente") {
            header("Location: homeUtente.php");
            exit();
        } 
        elseif ($_SESSION["user_tipo"] === "personal_trainer") {
            header("Location: homePT.php");
            exit();
        } 
        elseif ($_SESSION["user_tipo"] === "admin") {
            header("Location: homeAdmin.php");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PulseCoach</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="icon" type="image/png" href="../immagini/icona_schede.jpg">
    <script src='../js/login.js'></script>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h1>Login</h1>
            <form onsubmit="validaLogin(event)">
                <div class="input-group">
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit">Accedi</button>
                <div class="footer">
                    <p>Non hai un account? <a href="../html/registrazioneUtente.html">Registrati qui</a></p>
                    <p>Sei un Personal Trainer? <a href="../html/registrazionePT.html">Candidati ora</a></p>
                    <p>Hai dimenticato la password? <a href="../html/recuperaPassword.html">Recuperala qui</a></p>
                    <p><a href="../html/guida.html">Guida</a></p>
                </div>
                <p id="warning"></p>
            </form>
        </div>
        <div class="login-image">
            <img src="../immagini/logo.png" alt="Logo PulseCoach">
        </div>
    </div>
</body>
</html>