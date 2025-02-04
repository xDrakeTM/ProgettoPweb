<?php
    require_once '../utility/getDBConnection.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        die(json_encode(['success' => false, 'message' => 'Utente non autenticato']));
    }

    if (!isset($_GET['appuntamento_id'])) {
        die(json_encode(['success' => false, 'message' => 'ID appuntamento non specificato']));
    }

    $appuntamento_id = $_GET['appuntamento_id'];

    $conn = getDBConnection();
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Connessione al database fallita: ' . $conn->connect_error]));
    }

    // Verifica se la richiesta è di tipo POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recupera i dati inviati tramite POST
        $tipo_obiettivo = $_POST['tipo_obiettivo'];
        $obiettivo = $_POST['obiettivo'];
        $descrizione = $_POST['descrizione'];
        $ripetizioni = isset($_POST['ripetizioni']) ? $_POST['ripetizioni'] : null;
        $serie = isset($_POST['serie']) ? $_POST['serie'] : null;
        $peso = isset($_POST['peso']) ? $_POST['peso'] : null;

        $progresso1 = isset($_POST['progresso']) ? $_POST['progresso'] : null;
        $progresso2 = null;
        $progresso3 = null;

        // Prepara la query SQL per inserire un nuovo obiettivo
        $sql = "INSERT INTO obiettivi (appuntamento_id, tipo_obiettivo, obiettivo, descrizione, ripetizioni, serie, peso, progresso1, progresso2, progresso3) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die(json_encode(['success' => false, 'message' => 'Errore nella preparazione della query: ' . $conn->error]));
        }
        // Associa i parametri alla query
        $stmt->bind_param("isssiiiddd", $appuntamento_id, $tipo_obiettivo, $obiettivo, $descrizione, $ripetizioni, $serie, $peso, $progresso1, $progresso2, $progresso3);

        // Esegue la query e verifica se è stata eseguita con successo
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Obiettivo salvato con successo']);
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Errore durante il salvataggio dell\'obiettivo: ' . $stmt->error]);
        }

        // Chiude lo statement
        $stmt->close();
    }

    // Chiude la connessione al database
    $conn->close();
?>