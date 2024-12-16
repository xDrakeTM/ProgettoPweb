<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["user_id"])) {
        die(json_encode(["success" => false, "message" => "Utente non autenticato"]));
    }

    $conn = new mysqli("localhost", "root", "", "carinci_635710");

    if ($conn->connect_error) {
        die(json_encode(["success" => false, "message" => "Connessione fallita: " . $conn->connect_error]));
    }

    try {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user_id = $_SESSION['user_id'];
            $id_pt = $_POST['id_pt'];
            $data = $_POST['data'];
            $ora = $_POST['ora'];
            $stato = 'prenotato';

            $stmt = $conn->prepare("INSERT INTO appuntamento (utente_id, personal_trainer_id, data, ora, stato) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisss", $user_id, $id_pt, $data, $ora, $stato);
            
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Appuntamento registrato con successo!"]);
            } 
            else {
                echo json_encode(["success" => false, "message" => "Errore durante la registrazione dell'appuntamento!"]);
            }
        }
    } 
    catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Errore durante la prenotazione: " . $e->getMessage()]);
    }

    $conn->close();
?>