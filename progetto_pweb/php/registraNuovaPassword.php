<?php
    include '../utility/getDBConnection.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["user_id"])) {
        echo json_encode(["success" => false, "message" => "Utente non autenticato."]);
        exit();
    }

    $conn = getDBConnection();

    if ($conn->connect_error) {
        echo json_encode(["success" => false, "message" => "Connessione al database fallita."]);
        exit();
    }

    $user_id = $_SESSION["user_id"];
    $user_tipo = $_SESSION["user_tipo"];
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if ($new_password !== $confirm_password) {
        echo json_encode(["success" => false, "message" => "Le password non coincidono."]);
        exit;
    }

    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_password)) {
        echo json_encode([
            "success" => false,
            "message" => "La password deve avere almeno 8 caratteri, una lettera maiuscola, una minuscola, un numero e un carattere speciale."
        ]);
        exit;
    }

    $table = $user_tipo == 'utente' ? 'utente' : 'personal_trainer';
    $stmt = $conn->prepare("SELECT password FROM $table WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($old_password, $user["password"])) {
        echo json_encode(["success" => false, "message" => "La vecchia password è errata."]);
        exit();
    }

    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

    $update_stmt = $conn->prepare("UPDATE $table SET password = ? WHERE id = ?");
    $update_stmt->bind_param("si", $hashed_new_password, $user_id);

    if ($update_stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Password aggiornata con successo!"]);
    } 
    else {
        echo json_encode(["success" => false, "message" => "Errore durante l'aggiornamento della password!"]);
    }

    $update_stmt->close();
    $conn->close();
    session_destroy();
?>