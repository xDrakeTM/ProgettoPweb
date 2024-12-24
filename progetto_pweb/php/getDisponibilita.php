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

        $stmt = $conn->prepare("SELECT data, ora FROM appuntamento WHERE personal_trainer_id = ?");
        $stmt->bind_param("i", $id_pt);
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