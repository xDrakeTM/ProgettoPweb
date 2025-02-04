<?php
    // Include il file per ottenere la connessione al database
    require_once '../utility/getDBConnection.php';

    // Avvia la sessione se non è già stata avviata
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verifica se l'utente è autenticato
    if (!isset($_SESSION["user_id"])) {
        die(json_encode(["success" => false, "message" => "Utente non autenticato."]));
    }

    // Ottiene la connessione al database
    $conn = getDBConnection();

    // Verifica se ci sono errori di connessione
    if ($conn->connect_error) {
        die(json_encode(["success" => false, "message" => "Connessione al database fallita: " . $conn->connect_error]));
    }

    // Recupera l'ID dell'utente dalla sessione
    $user_id = $_SESSION["user_id"];
    
    // Prepara la query SQL per selezionare gli appuntamenti dell'utente
    $stmt = $conn->prepare("SELECT DATE_FORMAT(a.data, '%d/%m/%Y') as data, TIME_FORMAT(a.ora, '%H:%i') as ora_inizio, TIME_FORMAT((a.ora + INTERVAL 1 HOUR), '%H:%i') as ora_fine, pt.nome, pt.cognome, a.stato 
                            FROM appuntamento a 
                            JOIN personal_trainer pt ON a.personal_trainer_id = pt.id 
                            WHERE a.utente_id = ? 
                            ORDER BY a.data, a.ora");
    // Associa l'ID dell'utente al parametro della query
    $stmt->bind_param("i", $user_id);
    // Esegue la query
    $stmt->execute();
    // Ottiene il risultato della query
    $result = $stmt->get_result();

    // Inizializza un array per memorizzare gli appuntamenti
    $appuntamenti = [];

    // Itera sui risultati della query e li aggiunge all'array degli appuntamenti
    while ($row = $result->fetch_assoc()) {
        $appuntamenti[] = $row;
    }

    // Restituisce gli appuntamenti in formato JSON
    echo json_encode($appuntamenti);

    // Chiude la connessione al database
    $conn->close();
?>