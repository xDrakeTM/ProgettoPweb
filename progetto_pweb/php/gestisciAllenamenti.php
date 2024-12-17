<?php
    include '../utility/functions.php';
    include '../utility/getDBConnection.php';

    controllaPT('homeUtente');

    $q = function() use ($conn) {
        global $user;
        $user_id = $_SESSION["user_id"];

        $conn = getDBConnection();
        if ($conn->connect_error) {
            die("Errore di connessione al database: " . $conn->connect_error);
        }

        // cancella appuntamenti scaduti
        $sql = "UPDATE appuntamento 
                SET stato = 'cancellato' 
                WHERE personal_trainer_id = ? 
                AND stato = 'prenotato' 
                AND data < CURRENT_DATE";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        $sql = "SELECT a.id, CONCAT(u.nome, ' ', u.cognome) as utente, DATE_FORMAT(a.data, '%d/%m/%Y') as data, 
                TIME_FORMAT(a.ora, '%H:%i') as ora_inizio, TIME_FORMAT((a.ora + INTERVAL 1 HOUR), '%H:%i') as ora_fine 
                FROM appuntamento a 
                INNER JOIN utente u ON a.utente_id = u.id 
                WHERE a.personal_trainer_id = ? AND a.stato = 'prenotato'";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user = $stmt->get_result();
    };

    _header('Gestisci Allenamenti', 
    '<link rel="stylesheet" href="../css/utility.css">
    <script src="../js/gestisciAllenamenti.js"></script>', $q);
    menuPT();
?>
<main>
    <section class="welcome-section">
        <h1>Richieste di Allenamento</h1>

        <table>
            <thead>
                <tr>
                    <th>Utente</th>
                    <th>Data</th>
                    <th>Ora Inizio</th>
                    <th>Ora Fine</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $user->fetch_assoc()) : ?>
                    <tr id="appuntamento-<?php echo $row['id']; ?>">
                        <td><?php echo htmlspecialchars($row['utente'] . ' ' . $row['cognome']); ?></td>
                        <td><?php echo htmlspecialchars($row['data']); ?></td>
                        <td><?php echo htmlspecialchars($row['ora_inizio']); ?></td>
                        <td><?php echo htmlspecialchars($row['ora_fine']); ?></td>
                        <td>
                            <button class="conferma" onclick="confermaAppuntamento(<?php echo $row['id']; ?>)">Conferma</button>
                            <button class="rifiuta" onclick="rifiutaAppuntamento(<?php echo $row['id']; ?>)">Rifiuta</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>

<?php
    _footer();
?>