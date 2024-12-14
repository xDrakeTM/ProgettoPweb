<?php
    include '../utility/functions.php';

    controllaUtente('homePT');

    $conn = new mysqli("localhost", "root", "", "carinci_635710");
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    $user_id = $_SESSION["user_id"];
    $stmt = $conn->prepare("SELECT data_emissione_certificato FROM utente WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    $data_emissione = new DateTime($user['data_emissione_certificato']);
    $data_scadenza = (clone $data_emissione)->modify('+12 months');
    $oggi = new DateTime();
    $certificato_scaduto = $oggi > $data_scadenza;

    _header('Prenota un Appuntamento', '
    <link rel="stylesheet" href="../css/prenotazione.css">
    <script src="../js/prenotazione.js"></script>');
    menuUtente();
?>

<main>
    <section class="welcome-section">
        <div class="form-container">
            <h1>Prenota un Appuntamento</h1>
            <?php if ($certificato_scaduto): ?>
                <p style="color: red;">Il tuo certificato medico è scaduto. Non puoi prenotare un appuntamento finché non aggiorni il tuo certificato.</p>
            <?php else: ?>
                <form id="bookingForm" onsubmit="prenotaAppuntamento(event)">
                    <label for="personal_trainer">Seleziona un Personal Trainer:</label>
                    <select id="personal_trainer" name="personal_trainer" required onchange="caricaCurriculum(this.value)">
                        <option value="">Seleziona un PT...</option>
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