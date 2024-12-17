<?php
    include '../utility/functions.php';
    include '../utility/getDBConnection.php';

    if (!isset($_GET['id'])) {
        echo json_encode(["success" => false, "message" => "ID del personal trainer mancante."]);
        exit();
    }

    $conn = getDBConnection();
    if ($conn->connect_error) {
        die(json_encode(["success" => false, "message" => "Connessione al database fallita: " . $conn->connect_error]));
    }

    $pt_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT curriculum FROM personal_trainer WHERE id = ?");
    $stmt->bind_param("i", $pt_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pt = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    if ($pt) {
        echo json_encode(["success" => true, "curriculum" => $pt['curriculum']]);
    } 
    else {
        echo json_encode(["success" => false, "message" => "Personal trainer non trovato."]);
    }
?>