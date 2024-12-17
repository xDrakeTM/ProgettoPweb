<?php
    include '../utility/functions.php';

    controllaAdmin();

    $conn = new mysqli("localhost", "root", "", "carinci_635710");
    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }

    // Controlla se oggi è il primo giorno del mese
    if (date('j') == 1) {
        // Elimina i personal trainer non accettati da più di un mese
        $sql = "DELETE FROM personal_trainer 
                WHERE attivo = 0 
                AND timestamp_creazione < NOW() - INTERVAL 1 MONTH";

        if ($conn->query($sql) === TRUE) {
            echo "Personal trainer non accettati eliminati con successo.";
        } 
        else {
            echo "Errore durante l'eliminazione dei personal trainer non accettati: " . $conn->error;
        }
    }

    $sql = "SELECT id, nome, cognome, email, data_nascita, genere, cellulare, curriculum, timestamp_login, timestamp_logout, timestamp_creazione, timestamp_aggiornamento, attivo FROM personal_trainer";
    $result = $conn->query($sql);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['attiva_pt'])) {
        $pt_id = $_POST['pt_id'];
        $attivo = $_POST['attivo'];

        $stmt = $conn->prepare("UPDATE personal_trainer SET attivo = ? WHERE id = ?");
        $stmt->bind_param("ii", $attivo, $pt_id);
        if ($stmt->execute()) {
            header("Location: gestionePT.php");
            exit();
        } else {
            echo "Errore durante l'aggiornamento dello stato del PT: " . $stmt->error;
        }
        $stmt->close();
    }

    $conn->close();

    _header('Gestione Personal Trainer', 
    '<link rel="stylesheet" href="../css/utility.css">
    <script src="../js/gestioneUtentiPT.js"></script>');
    menuAdmin();
?>

<main>
    <section class="welcome-section">
        <h2>Gestione Personal Trainer</h2>
    </section>

    <section class="filter-section">
        <input type="text" class="filter-input" id="filter-input" placeholder="Filtra...">
    </section>

    <section class="pt-section">
        <table style="font-size: 10px;">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Data di Nascita</th>
                    <th>Genere</th>
                    <th>Cellulare</th>
                    <th>Curriculum</th>
                    <th>Timestamp Login</th>
                    <th>Timestamp Logout</th>
                    <th>Timestamp Creazione Account</th>
                    <th>Timestamp Aggiornamento</th>
                    <th>Stato</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody id="TableBody">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nome']) . ' ' . htmlspecialchars($row['cognome']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['data_nascita']); ?></td>
                            <td><?php echo htmlspecialchars($row['genere']); ?></td>
                            <td><?php echo htmlspecialchars($row['cellulare']); ?></td>
                            <td><a href="../curriculum/<?php echo htmlspecialchars($row['curriculum']); ?>" target="_blank">Visualizza Curriculum</a></td>
                            <td><?php echo htmlspecialchars($row['timestamp_login']); ?></td>
                            <td><?php echo htmlspecialchars($row['timestamp_logout']); ?></td>
                            <td><?php echo htmlspecialchars($row['timestamp_creazione']); ?></td>
                            <td><?php echo htmlspecialchars($row['timestamp_aggiornamento']); ?></td>
                            <td><?php echo $row['attivo'] ? 'Attivo' : 'Non Attivo'; ?></td>
                            <td>
                                <form method="POST" action="gestionePT.php">
                                    <input type="hidden" name="pt_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="attivo" value="<?php echo $row['attivo'] ? 0 : 1; ?>">
                                    <button class="<?php echo $row['attivo'] ? 'rifiuta' : 'conferma'; ?>" type="submit" name="attiva_pt" class="button"><?php echo $row['attivo'] ? 'Disattiva' : 'Attiva'; ?></button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12">Nessun personal trainer trovato.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

<?php
    _footer();
?>