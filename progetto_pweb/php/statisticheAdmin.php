<?php
    include '../utility/functions.php';

    session_start();
    controllaAdmin();

    $conn = new mysqli("localhost", "root", "", "carinci_635710");
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    // Recupera il numero totale di utenti
    $sql = "SELECT COUNT(*) AS total_users FROM utente";
    $result = $conn->query($sql);
    $total_users = $result->fetch_assoc()['total_users'];

    // Recupera il numero totale di personal trainer
    $sql = "SELECT COUNT(*) AS total_pts FROM personal_trainer";
    $result = $conn->query($sql);
    $total_pts = $result->fetch_assoc()['total_pts'];

    // Recupera il numero totale di accessi degli utenti
    $sql = "SELECT SUM(accessi) AS total_user_logins FROM utente";
    $result = $conn->query($sql);
    $total_user_logins = $result->fetch_assoc()['total_user_logins'];

    // Recupera il numero totale di accessi dei personal trainer
    $sql = "SELECT SUM(accessi) AS total_pt_logins FROM personal_trainer";
    $result = $conn->query($sql);
    $total_pt_logins = $result->fetch_assoc()['total_pt_logins'];

    // Recupera i dati delle registrazioni mensili degli utenti
    $sql = "SELECT MONTH(timestamp_creazione) AS mese, COUNT(*) AS registrazioni FROM utente GROUP BY MONTH(timestamp_creazione)";
    $result = $conn->query($sql);
    $registrazioni_utenti = array_fill(0, 12, 0);
    while ($row = $result->fetch_assoc()) {
        $registrazioni_utenti[$row['mese'] - 1] = $row['registrazioni'];
    }

    // Recupera i dati delle registrazioni mensili dei personal trainer
    $sql = "SELECT MONTH(timestamp_creazione) AS mese, COUNT(*) AS registrazioni FROM personal_trainer GROUP BY MONTH(timestamp_creazione)";
    $result = $conn->query($sql);
    $registrazioni_pts = array_fill(0, 12, 0);
    while ($row = $result->fetch_assoc()) {
        $registrazioni_pts[$row['mese'] - 1] = $row['registrazioni'];
    }

    $conn->close();

    _header('Statistiche Admin', '<link rel="stylesheet" href="../css/statistiche.css">');
    menuAdmin();
?>

<main>
    <section class="welcome-section">
        <h2>Statistiche della Piattaforma</h2>
    </section>

    <section class="stats-section">
        <div class="stat-box">
            <h3>Utenti Registrati:</h3><br>
            <p><?php echo $total_users; ?></p>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const registrazioniUtenti = <?php echo json_encode($registrazioni_utenti); ?>;
    const registrazioniPTs = <?php echo json_encode($registrazioni_pts); ?>;
</script>
<script src="../js/statisticheAdmin.js"></script>

<?php
    _footer();
?>