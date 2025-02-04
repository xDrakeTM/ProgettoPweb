<?php
    // Include il file delle funzioni utilitarie
    require_once '../utility/functions.php';
    // Include il file per ottenere la connessione al database
    require_once '../utility/getDBConnection.php';

    // Controlla se l'utente ha i permessi per accedere a questa pagina
    controllaUtente('homePT');

    // Ottiene la connessione al database
    $conn = getDBConnection();
    // Verifica se ci sono errori di connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    // Recupera l'ID dell'utente dalla sessione
    $user_id = $_SESSION["user_id"];
    // Prepara la query SQL per recuperare la data di emissione del certificato medico dell'utente
    $stmt = $conn->prepare("SELECT data_emissione_certificato FROM utente WHERE id = ?");
    // Associa l'ID dell'utente al parametro della query
    $stmt->bind_param("i", $user_id);
    // Esegue la query
    $stmt->execute();
    // Ottiene il risultato della query
    $result = $stmt->get_result();
    // Recupera i dati dell'utente
    $user = $result->fetch_assoc();
    // Chiude lo statement
    $stmt->close();

    // Calcola la data di scadenza del certificato medico
    $data_emissione = new DateTime($user['data_emissione_certificato']);
    $data_scadenza = (clone $data_emissione)->modify('+12 months');
    $oggi = new DateTime();
    $certificato_scaduto = $oggi > $data_scadenza;

    // Include l'header della pagina con il titolo e i link ai file CSS e JS
    _header('Prenota un Appuntamento', '
    <link rel="stylesheet" href="../css/prenotazione.css">
    <script src="../js/prenotazione.js"></script>');
    // Include il menu dell'utente
    menuUtente();
?>

<main>
    <section class="welcome-section">
        <div class="form-container">
            <h1>Prenota un Appuntamento</h1>
            <?php if ($certificato_scaduto): ?>
                <!-- Messaggio di avviso se il certificato medico è scaduto -->
                <p style="color: red;">Il tuo certificato medico è scaduto. Non puoi prenotare un appuntamento finché non aggiorni il tuo certificato.</p>
            <?php else: ?>
                <!-- Form per prenotare un appuntamento -->
                <form id="bookingForm" onsubmit="prenotaAppuntamento(event)">
                    <label for="personal_trainer">Seleziona un Personal Trainer:</label>
                    <select id="personal_trainer" name="personal_trainer" required onchange="caricaCurriculum(this.value)">
                        <option value="">Seleziona un PT...</option>
                        <!-- Le opzioni dei personal trainer verranno caricate dinamicamente -->
                    </select>

                    <div id="curriculum" style="margin-top: 10px; margin-bottom: 10px;"></div>

                    <label for="data">Seleziona una data:</label>
                    <input type="date" id="data" name="data" required disabled>

                    <label for="ora">Seleziona un orario:</label>
                    <select id="ora" name="ora" required disabled>
                        <option value="">Seleziona un orario...</option>
                        <option value="09:00:00">09:00</option>
                        <option value="10:00:00">10:00</option>
                        <option value="11:00:00">11:00</option>
                        <option value="12:00:00">12:00</option>
                        <option value="14:00:00">14:00</option>
                        <option value="15:00:00">15:00</option>
                        <option value="16:00:00">16:00</option>
                        <option value="17:00:00">17:00</option>
                        <option value="18:00:00">18:00</option>
                        <option value="19:00:00">19:00</option>
                        <option value="20:00:00">20:00</option>
                        <option value="21:00:00">21:00</option>
                    </select>

                    <button type="submit">Prenota</button>
                </form>
            <?php endif; ?>
            <div id="status"></div>
        </div>
    </section>
</main>
<?php
    _footer();
?>