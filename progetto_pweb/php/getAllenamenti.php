<?php
    session_start();

    if (!isset($_SESSION["user_id"])) {
        die(json_encode(["success" => false, "message" => "Utente non autenticato."]));
    }

    $conn = new mysqli("localhost", "root", "", "carinci_635710");

    if ($conn->connect_error) {
        die(json_encode(["success" => false, "message" => "Connessione al database fallita: " . $conn->connect_error]));
    }

    $user_id = $_SESSION["user_id"];
    
    $stmt = $conn->prepare("SELECT DATE_FORMAT(a.data, '%d/%m/%Y') as data, TIME_FORMAT(a.ora, '%H:%i') as ora_inizio, TIME_FORMAT((a.ora + INTERVAL 1 HOUR), '%H:%i') as ora_fine, pt.nome, pt.cognome, a.stato 
                            FROM appuntamento a 
                            JOIN personal_trainer pt ON a.personal_trainer_id = pt.id 
                            WHERE a.utente_id = ? 
                            ORDER BY a.data, a.ora");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $appuntamenti = [];

    while ($row = $result->fetch_assoc()) {
        $appuntamenti[] = $row;
    }

    echo json_encode($appuntamenti);

    $conn->close();
?>