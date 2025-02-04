<?php
    // Include il file per ottenere la connessione al database
    require_once '../utility/getDBConnection.php';

    // Avvia la sessione se non è già stata avviata
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verifica se l'utente è autenticato
    if (!isset($_SESSION["user_id"])) {
        die(json_encode(["success" => false, "message" => "Utente non autenticato"]));
    }

    // Ottiene la connessione al database
    $conn = getDBConnection();

    // Verifica se ci sono errori di connessione
    if ($conn->connect_error) {
        die(json_encode([
            "success" => false,
            "message" => "Connessione al database fallita: " . $conn->connect_error
        ]));
    }

    // Verifica se l'ID del personal trainer è stato specificato
    if (isset($_GET['id_pt'])) {
        $id_pt = $_GET['id_pt'];
        $user_id = $_SESSION["user_id"];

        // Prepara la query SQL per selezionare le disponibilità del personal trainer
        $stmt = $conn->prepare("
            SELECT data, ora 
            FROM appuntamento 
            WHERE (personal_trainer_id = ? AND stato = 'confermato') 
            OR (utente_id = ? AND stato = 'prenotato')
        ");
        // Associa l'ID del personal trainer e l'ID dell'utente ai parametri della query
        $stmt->bind_param("ii", $id_pt, $user_id);
        // Esegue la query
        $stmt->execute();
        // Ottiene il risultato della query
        $result = $stmt->get_result();

        // Inizializza un array per memorizzare le disponibilità
        $disponibilita = [];

        // Itera sui risultati della query e li aggiunge all'array delle disponibilità
        while ($row = $result->fetch_assoc()) {
            $disponibilita[] = $row;
        }

        // Restituisce le disponibilità in formato JSON
        echo json_encode($disponibilita);
    } else {
        // Restituisce un messaggio di errore se l'ID del personal trainer non è stato specificato
        echo json_encode(["success" => false, "message" => "ID personal trainer non specificato"]);
    }

    // Chiude la connessione al database
    $conn->close();
?>