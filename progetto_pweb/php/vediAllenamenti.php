<?php
    include '../utility/getDBConnection.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        die(json_encode(['success' => false, 'message' => 'Utente non autenticato']));
    }

    $conn = getDBConnection();
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Connessione al database fallita']));
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $stato = $_POST['stato'];

        $stmt = $conn->prepare("UPDATE appuntamento SET stato = ? WHERE id = ?");
        $stmt->bind_param("si", $stato, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Appuntamento aggiornato']);
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento dell\'appuntamento']);
        }
    }

    $conn->close();
?>