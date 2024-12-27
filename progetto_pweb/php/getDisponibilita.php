<?php
    require_once '../utility/getDBConnection.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["user_id"])) {
        die(json_encode(["success" => false, "message" => "Utente non autenticato"]));
    }

    $conn = getDBConnection();

    if ($conn->connect_error) {
        die(json_encode([
            "success" => false,
            "message" => "Connessione al database fallita: " . $conn->connect_error
        ]));
    }

    if (isset($_GET['id_pt'])) {
        $id_pt = $_GET['id_pt'];
        $user_id = $_SESSION["user_id"];

        // Recupera le disponibilità del personal trainer e gli appuntamenti dell'utente
        $stmt = $conn->prepare("
            SELECT data, ora 
            FROM appuntamento 
            WHERE (personal_trainer_id = ? AND stato = 'confermato') 
            OR (utente_id = ? AND stato = 'prenotato')
        ");
        $stmt->bind_param("ii", $id_pt, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $disponibilita = [];

        while ($row = $result->fetch_assoc()) {
            $disponibilita[] = $row;
        }

        echo json_encode($disponibilita);
    }

    $conn->close();
?>