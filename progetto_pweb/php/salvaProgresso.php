<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        die(json_encode(['success' => false, 'message' => 'Utente non autenticato']));
    }

    $conn = new mysqli("localhost", "root", "", "carinci_635710");
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . $conn->connect_error]));
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $progresso1 = isset($_POST['progresso1']) ? $_POST['progresso1'] : null;
        $progresso2 = isset($_POST['progresso2']) ? $_POST['progresso2'] : null;
        $progresso3 = isset($_POST['progresso3']) ? $_POST['progresso3'] : null;

        $stmt = $conn->prepare("UPDATE obiettivi SET progresso1 = ?, progresso2 = ?, progresso3 = ? WHERE id = ?");
        $stmt->bind_param("dddi", $progresso1, $progresso2, $progresso3, $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Progresso salvato con successo']);
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Errore durante il salvataggio del progresso']);
        }
        $stmt->close();
    }

    $conn->close();
?>