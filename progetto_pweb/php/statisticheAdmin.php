<?php
    require_once '../utility/functions.php';
    require_once '../utility/getDBConnection.php';
    
    controllaAdmin();

    $conn = getDBConnection();
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    $currentYear = date('Y');
    $currentMonth = date('m');
    $selectedYear = isset($_GET['anno']) ? $_GET['anno'] : $currentYear;
    $selectedMonth = isset($_GET['mese']) ? $_GET['mese'] : $currentMonth;

    // Recupera il numero totale di utenti registrati nel mese e anno selezionati
    $sql = "SELECT COUNT(*) AS total_users FROM utente WHERE YEAR(timestamp_creazione) = ? AND MONTH(timestamp_creazione) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $selectedYear, $selectedMonth);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_users = $result->fetch_assoc()['total_users'];

    // Recupera il numero totale di personal trainer registrati nel mese e anno selezionati
    $sql = "SELECT COUNT(*) AS total_pts FROM personal_trainer WHERE YEAR(timestamp_creazione) = ? AND MONTH(timestamp_creazione) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $selectedYear, $selectedMonth);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_pts = $result->fetch_assoc()['total_pts'];

    // Recupera il numero totale di accessi degli utenti nel mese e anno selezionati
    $sql = "SELECT COUNT(*) AS total_user_logins FROM accessi WHERE user_tipo = 'utente' AND YEAR(timestamp_accesso) = ? AND MONTH(timestamp_accesso) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $selectedYear, $selectedMonth);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_user_logins = $result->fetch_assoc()['total_user_logins'];

    // Recupera il numero totale di accessi dei personal trainer nel mese e anno selezionati
    $sql = "SELECT COUNT(*) AS total_pt_logins FROM accessi WHERE user_tipo = 'personal_trainer' AND YEAR(timestamp_accesso) = ? AND MONTH(timestamp_accesso) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $selectedYear, $selectedMonth);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_pt_logins = $result->fetch_assoc()['total_pt_logins'];

    // Recupera i dati delle registrazioni mensili degli utenti
    $sql = "SELECT MONTH(timestamp_creazione) AS mese, COUNT(*) AS registrazioni 
            FROM utente 
            WHERE YEAR(timestamp_creazione) = ? AND MONTH(timestamp_creazione) = ?
            GROUP BY MONTH(timestamp_creazione)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $selectedYear, $selectedMonth);
    $stmt->execute();
    $result = $stmt->get_result();
    $registrazioni_utenti = array_fill(0, 12, 0);
    while ($row = $result->fetch_assoc()) {
        $registrazioni_utenti[$row['mese'] - 1] = $row['registrazioni'];
    }

    // Query per ottenere il numero di registrazioni per mese nel corso dell'anno selezionato
    $sql = "SELECT MONTH(timestamp_creazione) AS mese, COUNT(*) AS registrazioni 
            FROM personal_trainer 
            WHERE YEAR(timestamp_creazione) = ? AND MONTH(timestamp_creazione) = ?
            GROUP BY MONTH(timestamp_creazione)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $selectedYear, $selectedMonth);
    $stmt->execute();
    $result = $stmt->get_result();
    $registrazioni_pts = array_fill(0, 12, 0);
    while ($row = $result->fetch_assoc()) {
        $registrazioni_pts[$row['mese'] - 1] = $row['registrazioni'];
    }
    $stmt->close();

    $conn->close();

    _header('Statistiche Admin', '<link rel="stylesheet" href="../css/statistiche.css">');
    menuAdmin();
?>

<main>
    <section class="welcome-section">
        <h2>Statistiche della Piattaforma</h2>
    </section>

    <section class="filter-section">
        <form method="GET" action="statisticheAdmin.php">
        <label for="mese">Mese:</label>
            <select class="filter-select" id="mese" name="mese">
                <option value="1" <?php if ($selectedMonth == 1) echo 'selected'; ?>>Gennaio</option>
                <option value="2" <?php if ($selectedMonth == 2) echo 'selected'; ?>>Febbraio</option>
                <option value="3" <?php if ($selectedMonth == 3) echo 'selected'; ?>>Marzo</option>
                <option value="4" <?php if ($selectedMonth == 4) echo 'selected'; ?>>Aprile</option>
                <option value="5" <?php if ($selectedMonth == 5) echo 'selected'; ?>>Maggio</option>
                <option value="6" <?php if ($selectedMonth == 6) echo 'selected'; ?>>Giugno</option>
                <option value="7" <?php if ($selectedMonth == 7) echo 'selected'; ?>>Luglio</option>
                <option value="8" <?php if ($selectedMonth == 8) echo 'selected'; ?>>Agosto</option>
                <option value="9" <?php if ($selectedMonth == 9) echo 'selected'; ?>>Settembre</option>
                <option value="10" <?php if ($selectedMonth == 10) echo 'selected'; ?>>Ottobre</option>
                <option value="11" <?php if ($selectedMonth == 11) echo 'selected'; ?>>Novembre</option>
                <option value="12" <?php if ($selectedMonth == 12) echo 'selected'; ?>>Dicembre</option>
            </select>
            <label for="anno">Anno:</label>
            <select class="filter-select" id="anno" name="anno">
                <?php for ($y = 2020; $y <= date('Y'); $y++): ?>
                    <option value="<?php echo $y; ?>" <?php if ($y == $selectedYear) echo 'selected'; ?>>
                        <?php echo $y; ?>
                    </option>
                <?php endfor; ?>
            </select>
            <button class="filter-button" type="submit">Filtra</button>
        </form>
    </section>

    <section class="stats-section">
        <div class="stat-box">
            <h3>Utenti Registrati:</h3>
            <p style="margin-top: 20px;"><?php echo $total_users; ?></p>
        </div>
        <div class="stat-box">
            <h3>Personal Trainer Registrati:</h3>
            <p><?php echo $total_pts; ?></p>
        </div>
        <div class="stat-box">
            <h3>Accessi Totali Utenti:</h3>
            <p><?php echo $total_user_logins; ?></p>
        </div>
        <div class="stat-box">
            <h3>Accessi Totali Personal Trainer:</h3>
            <p><?php echo $total_pt_logins; ?></p>
        </div>
    </section>

    <section style="margin-top: 20px;" class="charts-section">
        <h3>Andamento delle Registrazioni</h3>
        <canvas id="registrationsChart"></canvas>
    </section>
</main>

<!- Parte non originale -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const registrazioniUtenti = <?php echo json_encode($registrazioni_utenti); ?>;
    const registrazioniPTs = <?php echo json_encode($registrazioni_pts); ?>;
</script>
<script src="../js/statisticheAdmin.js"></script>

<?php
    _footer();
?>
