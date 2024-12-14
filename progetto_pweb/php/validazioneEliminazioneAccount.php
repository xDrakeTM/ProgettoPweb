<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["user_id"]) && !isset($_POST["user_id"])) {
        header("Location: ../php/login.php");
        exit();
    }

    $conn = new mysqli("localhost", "root", "", "carinci_635710");

    if ($conn->connect_error) {
        die(json_encode(["success" => false, "message" => "Connessione fallita: " . $conn->connect_error]));
    }

    $user_id = isset($_POST["user_id"]) ? $_POST["user_id"] : $_SESSION["user_id"];
    $user_tipo = isset($_POST["user_tipo"]) ? $_POST["user_tipo"] : $_SESSION["user_tipo"];

    try {
        $conn->begin_transaction();

        if ($user_tipo === 'utente' || $user_tipo === 'admin') {
            $stmt = $conn->prepare("SELECT certificato FROM utente WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $certificato = $result->fetch_assoc()['certificato'];
            $stmt->close();

            if ($certificato && file_exists(__DIR__ . '/../certificati/' . $certificato)) {
                unlink(__DIR__ . '/../certificati/' . $certificato);
            }

            $stmt = $conn->prepare("DELETE FROM utente WHERE id = ?");
        } 
        elseif ($user_tipo === 'personal_trainer') {
            $stmt = $conn->prepare("SELECT curriculum FROM personal_trainer WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $curriculum = $result->fetch_assoc()['curriculum'];
            $stmt->close();

            if ($curriculum && file_exists(__DIR__ . '/../curriculum/' . $curriculum)) {
                unlink(__DIR__ . '/../curriculum/' . $curriculum);
            }

            $stmt = $conn->prepare("DELETE FROM personal_trainer WHERE id = ?");
        } 
        else {
            throw new Exception("Ruolo utente non valido.");
        }

        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $conn->commit();
            if (!isset($_POST["user_id"])) {
                session_destroy();
            }
            echo json_encode(["success" => true, "message" => "Account eliminato con successo!"]);
        } 
        else {
            throw new Exception("Errore durante l'eliminazione dell'account: " . $stmt->error);
        }
    } 
    catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }

    $stmt->close();
    $conn->close();
?>