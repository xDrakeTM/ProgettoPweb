<?php
    require_once '../utility/functions.php';
    require_once '../utility/getDBConnection.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    controllaUtente('homePT');

    $user_id = $_SESSION["user_id"];

    $conn = getDBConnection();
    if ($conn->connect_error) {
        die("Errore di connessione al database: " . $conn->connect_error);
    }

    $currentMonth = date('m');
    $currentYear = date('Y');

    $selectedMonth = isset($_GET['mese']) ? $_GET['mese'] : $currentMonth;
    $selectedYear = isset($_GET['anno']) ? $_GET['anno'] : $currentYear;

    $sqlQuantitativi = "SELECT o.obiettivo, o.progresso1, o.progresso2, o.progresso3, o.ripetizioni, o.serie, o.peso 
                        FROM obiettivi o
                        JOIN appuntamento a ON o.appuntamento_id = a.id
                        WHERE a.utente_id = ? AND o.tipo_obiettivo = 'quantitativo'
                        AND o.progresso1 IS NOT NULL AND o.progresso2 IS NOT NULL AND o.progresso3 IS NOT NULL
                        AND MONTH(a.data) = ? AND YEAR(a.data) = ?";
    $stmtQuantitativi = $conn->prepare($sqlQuantitativi);
    $stmtQuantitativi->bind_param("iii", $user_id, $selectedMonth, $selectedYear);
    $stmtQuantitativi->execute();
    $resultQuantitativi = $stmtQuantitativi->get_result();
    $quantitativi = [];
    $quantitativiCompletati = 0;
    while ($row = $resultQuantitativi->fetch_assoc()) {
        if ($row['progresso1'] >= $row['ripetizioni'] && $row['progresso2'] >= $row['serie'] && $row['progresso3'] >= $row['peso']) {
            $quantitativiCompletati++;
        }
        $quantitativi[] = $row;
    }
    $stmtQuantitativi->close();

    $sqlContinuativi = "SELECT o.obiettivo, o.progresso1, o.progresso2 
                        FROM obiettivi o
                        JOIN appuntamento a ON o.appuntamento_id = a.id
                        WHERE a.utente_id = ? AND o.tipo_obiettivo = 'continuativo'
                        AND o.progresso1 IS NOT NULL AND o.progresso2 IS NOT NULL
                        AND MONTH(a.data) = ? AND YEAR(a.data) = ?";
    $stmtContinuativi = $conn->prepare($sqlContinuativi);
    $stmtContinuativi->bind_param("iii", $user_id, $selectedMonth, $selectedYear);
    $stmtContinuativi->execute();
    $resultContinuativi = $stmtContinuativi->get_result();
    $continuativi = [];
    $continuativiCompletati = 0;
    while ($row = $resultContinuativi->fetch_assoc()) {
        if ($row['progresso2'] >= $row['progresso1']) {
            $continuativiCompletati++;
        }
        $row['progresso2'] = ($row['progresso2'] / $row['progresso1']) * 100;
        $continuativi[] = $row;
    }
    $stmtContinuativi->close();

    $totalObiettivi = count($quantitativi) + count($continuativi);
    if ($totalObiettivi > 0) {
        $percentualeCompletati = ($quantitativiCompletati + $continuativiCompletati) / $totalObiettivi * 100;
    }
    else {
        $percentualeCompletati = 0;
    }
    
    $sqlMediaEserciziQuantitativi = "SELECT AVG(num_esercizi) as media_esercizi 
                                    FROM (SELECT COUNT(*) as num_esercizi 
                                    FROM obiettivi o
                                    JOIN appuntamento a ON o.appuntamento_id = a.id
                                    WHERE a.utente_id = ? AND o.tipo_obiettivo = 'quantitativo'
                                    AND MONTH(a.data) = ? AND YEAR(a.data) = ?
                                    GROUP BY o.appuntamento_id) as subquery";
    $stmtMediaEserciziQuantitativi = $conn->prepare($sqlMediaEserciziQuantitativi);
    $stmtMediaEserciziQuantitativi->bind_param("iii", $user_id, $selectedMonth, $selectedYear);
    $stmtMediaEserciziQuantitativi->execute();
    $resultMediaEserciziQuantitativi = $stmtMediaEserciziQuantitativi->get_result();
    $mediaEserciziQuantitativi = $resultMediaEserciziQuantitativi->fetch_assoc()['media_esercizi'];
    $stmtMediaEserciziQuantitativi->close();

    $conn->close();

    _header('Statistiche Mensili', 
    '<link rel="stylesheet" href="../css/statistiche.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../js/statistiche.js"></script>');
    menuUtente();
?>
<main>
    <section class="welcome-section">
        <h1>Statistiche dei Progressi Mensili</h1>
        <form method="GET" action="statistiche.php">
            <label style="font-weight: bold;" for="mese">Mese:</label>
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
            <label style="font-weight: bold;" for="anno">Anno:</label>
            <select class="filter-select" id="anno" name="anno">
                <?php for ($y = 2020; $y <= date('Y'); $y++): ?>
                    <option value="<?php echo $y; ?>" <?php if ($y == $selectedYear) echo 'selected'; ?>>
                        <?php echo $y; ?>
                    </option>
                <?php endfor; ?>
            </select>
            <button class="filter-button" type="submit">Filtra</button>
        </form>
        <div class="chart-container">
            <canvas id="percentualeCompletatiChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="mediaEserciziQuantitativiChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="progressoAllenamentiChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="statoContinuativiChart"></canvas>
        </div>
    </section>
</main>
<script>
    const percentualeCompletati = <?php echo json_encode($percentualeCompletati); ?>;
    const mediaEserciziQuantitativi = <?php echo json_encode($mediaEserciziQuantitativi); ?>;
    const quantitativiData = <?php echo json_encode($quantitativi); ?>;
    const continuativiData = <?php echo json_encode($continuativi); ?>;
</script>
<?php
    _footer();
?>