<?php
    // Include il file per ottenere la connessione al database
    require_once '../utility/getDBConnection.php';

    // Avvia la sessione se non è già stata avviata
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Ottiene la connessione al database
    $conn = getDBConnection();
    // Verifica se ci sono errori di connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    // Recupera il timestamp corrente
    $timestamp_logout = date('Y-m-d H:i:s');
    // Recupera l'email e il tipo di utente dalla sessione
    $user_email = $_SESSION["user_email"];
    $user_tipo = $_SESSION["user_tipo"];

    // Aggiorna il timestamp di logout solo se l'utente non è un amministratore
    if ($user_tipo != 'admin') {
        if ($user_tipo === 'utente') {
            // Prepara la query SQL per aggiornare il timestamp di logout per un utente
            $stmt = $conn->prepare("UPDATE utente SET timestamp_logout = ? WHERE email = ?");
        } 
        elseif ($user_tipo === 'personal_trainer') {
            // Prepara la query SQL per aggiornare il timestamp di logout per un personal trainer
            $stmt = $conn->prepare("UPDATE personal_trainer SET timestamp_logout = ? WHERE email = ?");
        }
    
        // Associa i parametri alla query
        $stmt->bind_param("ss", $timestamp_logout, $user_email);
        // Esegue la query
        $stmt->execute();
    }

    // Svuota l'array della sessione
    $_SESSION = [];
    // Distrugge la sessione
    session_destroy();

    // Reindirizza l'utente alla pagina di login
    header("Location: ../php/login.php");
    exit();
?>