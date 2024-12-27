<?php
    require_once '../utility/getDBConnection.php';

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

        // Recupera la data e l'ora dell'appuntamento
        $stmt = $conn->prepare("SELECT data, ora FROM appuntamento WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appuntamento = $result->fetch_assoc();
        $stmt->close();

        // Aggiorna lo stato dell'appuntamento
        $stmt = $conn->prepare("UPDATE appuntamento SET stato = ? WHERE id = ?");
        $stmt->bind_param("si", $stato, $id);

        if ($stmt->execute()) {
            // Se l'appuntamento è confermato, elimina gli altri appuntamenti alla stessa ora e data
            if ($stato === 'confermato') {
                $stmt = $conn->prepare("DELETE FROM appuntamento WHERE data = ? AND ora = ? AND id != ?");
                $stmt->bind_param("ssi", $appuntamento['data'], $appuntamento['ora'], $id);
                $stmt->execute();
            }
            echo json_encode(['success' => true, 'message' => 'Appuntamento aggiornato', 'data' => $appuntamento['data'], 'ora' => $appuntamento['ora']]);
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento dell\'appuntamento']);
        }
    }

    $conn->close();
?>