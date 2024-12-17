<?php
    include '../utility/functions.php';
    include '../utility/getDBConnection.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    controllaUtente('homePT');

    $user_id = $_SESSION["user_id"];

    $conn = getDBConnection();
    if ($conn->connect_error) {
        die("Errore di connessione al database: " . $conn->connect_error);
    }

    $sql = "SELECT a.id, CONCAT(p.nome, ' ', p.cognome) as pt, DATE_FORMAT(a.data, '%d/%m/%Y') as data, TIME_FORMAT(a.ora, '%H:%i') as ora_inizio, TIME_FORMAT((a.ora + INTERVAL 1 HOUR), '%H:%i') as ora_fine 
            FROM appuntamento a INNER JOIN personal_trainer p ON a.personal_trainer_id = p.id
            WHERE a.utente_id = ? AND a.stato = 'confermato'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $allenamenti = [];
    while ($row = $result->fetch_assoc()) {
        $allenamenti[] = $row;
    }
    $stmt->close();
    $conn->close();

    _header('Obiettivi', 
    '<link rel="stylesheet" href="../css/utility.css">
    <script src="../js/obiettivi.js"></script>');
    menuUtente();
?>
<main>
    <section class="welcome-section">
        <h1>Storico Allenamenti</h1>
        <?php if (empty($allenamenti)) : ?>
            <p style="margin-top: 20px;">Non ci sono allenamenti da visualizzare</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Personal Trainer</th>
                        <th>Data</th>
                        <th>Ora Inizio</th>
                        <th>Ora Fine</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allenamenti as $allenamento) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($allenamento['pt']); ?></td>
                            <td><?php echo htmlspecialchars($allenamento['data']); ?></td>
                            <td><?php echo htmlspecialchars($allenamento['ora_inizio']); ?></td>
                            <td><?php echo htmlspecialchars($allenamento['ora_fine']); ?></td>
                            <td><a style="font-weight: bold;" href="compilaObiettivi.php?allenamento_id=<?php echo $allenamento['id']; ?>">Compila Obiettivi</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</main>
<?php
    _footer();
?>