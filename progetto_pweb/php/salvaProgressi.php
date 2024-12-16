<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        die(json_encode(['success' => false, 'message' => 'Utente non autenticato']));
    }

    $conn = new mysqli("localhost", "root", "", "carinci_635710");
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Errore di connessione al database: ' . $conn->connect_error]));
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $progressi = $_POST['progresso'];

        foreach ($progressi as $id => $progresso) {
            $stmt = $conn->prepare("UPDATE obiettivi SET progresso = ? WHERE id = ?");
            $stmt->bind_param("ii", $progresso, $id);
            if (!$stmt->execute()) {
                echo json_encode(['success' => false, 'message' => 'Errore durante il salvataggio dei progressi']);
                $stmt->close();
                $conn->close();
                exit();
            }
            $stmt->close();
        }

        echo json_encode(['success' => true, 'message' => 'Progressi salvati con successo']);
    }

    $conn->close();
?>