<?php
    // Include il file per ottenere la connessione al database
    require_once '../utility/getDBConnection.php';

    // Avvia la sessione se non è già stata avviata
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verifica se l'utente è autenticato e se l'ID dell'utente è stato inviato tramite POST
    if (!isset($_SESSION["user_id"]) && !isset($_POST["user_id"])) {
        header("Location: ../php/login.php");
        exit();
    }

    // Ottiene la connessione al database
    $conn = getDBConnection();

    // Verifica se ci sono errori di connessione
    if ($conn->connect_error) {
        die(json_encode(["success" => false, "message" => "Connessione fallita: " . $conn->connect_error]));
    }

    // Recupera l'ID e il tipo di utente dalla sessione o dai dati POST
    $user_id = isset($_POST["user_id"]) ? $_POST["user_id"] : $_SESSION["user_id"];
    $user_tipo = isset($_POST["user_tipo"]) ? $_POST["user_tipo"] : $_SESSION["user_tipo"];

    try {
        // Inizia una transazione
        $conn->begin_transaction();

        if ($user_tipo === 'utente' || $user_tipo === 'admin') {
            // Prepara la query SQL per selezionare il certificato dell'utente
            $stmt = $conn->prepare("SELECT certificato FROM utente WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $certificato = $result->fetch_assoc()['certificato'];
            $stmt->close();

            // Verifica se il certificato esiste e lo elimina
            if ($certificato && file_exists(__DIR__ . '/../certificati/' . $certificato)) {
                unlink(__DIR__ . '/../certificati/' . $certificato);
            }

            // Prepara la query SQL per eliminare l'utente
            $stmt = $conn->prepare("DELETE FROM utente WHERE id = ?");
        } 
        elseif ($user_tipo === 'personal_trainer') {
            // Prepara la query SQL per selezionare il curriculum del personal trainer
            $stmt = $conn->prepare("SELECT curriculum FROM personal_trainer WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $curriculum = $result->fetch_assoc()['curriculum'];
            $stmt->close();

            // Verifica se il curriculum esiste e lo elimina
            if ($curriculum && file_exists(__DIR__ . '/../curriculum/' . $curriculum)) {
                unlink(__DIR__ . '/../curriculum/' . $curriculum);
            }

            // Prepara la query SQL per eliminare il personal trainer
            $stmt = $conn->prepare("DELETE FROM personal_trainer WHERE id = ?");
        }

        // Associa l'ID dell'utente al parametro della query
        $stmt->bind_param("i", $user_id);
        // Esegue la query
        $stmt->execute();
        // Commit della transazione
        $conn->commit();

        // Restituisce un messaggio di successo
        echo json_encode(["success" => true, "message" => "Account eliminato con successo"]);
    } catch (Exception $e) {
        // Rollback della transazione in caso di errore
        $conn->rollback();
        // Restituisce un messaggio di errore
        echo json_encode(["success" => false, "message" => "Errore durante l'eliminazione dell'account: " . $e->getMessage()]);
    }

    // Chiude la connessione al database
    $conn->close();
?>