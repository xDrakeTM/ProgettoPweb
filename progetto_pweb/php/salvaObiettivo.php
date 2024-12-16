<?php
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

    $conn = new mysqli("localhost", "root", "", "carinci_635710");
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Connessione al database fallita: ' . $conn->connect_error]));
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tipo_obiettivo = $_POST['tipo_obiettivo'];
        $obiettivo = $_POST['obiettivo'];
        $descrizione = $_POST['descrizione'];
        $ripetizioni = isset($_POST['ripetizioni']) ? $_POST['ripetizioni'] : null;
        $serie = isset($_POST['serie']) ? $_POST['serie'] : null;
        $peso = isset($_POST['peso']) ? $_POST['peso'] : null;

        $progresso1 = isset($_POST['progresso']) ? $_POST['progresso'] : null;
        $progresso2 = null;
        $progresso3 = null;

        $sql = "INSERT INTO obiettivi (appuntamento_id, tipo_obiettivo, obiettivo, descrizione, ripetizioni, serie, peso, progresso1, progresso2, progresso3) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die(json_encode(['success' => false, 'message' => 'Errore nella preparazione della query: ' . $conn->error]));
        }
        $stmt->bind_param("isssiiiddd", $appuntamento_id, $tipo_obiettivo, $obiettivo, $descrizione, $ripetizioni, $serie, $peso, $progresso1, $progresso2, $progresso3);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Obiettivo salvato con successo']);
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Errore durante il salvataggio dell\'obiettivo: ' . $stmt->error]);
        }

        $stmt->close();
    }

    $conn->close();
?>