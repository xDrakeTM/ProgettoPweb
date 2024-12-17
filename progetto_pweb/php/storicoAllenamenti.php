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

        $sql = "SELECT a.id, concat(u.nome, ' ', u.cognome) as utente, DATE_FORMAT(a.data, '%d/%m/%Y') as data, TIME_FORMAT(a.ora, '%H:%i') as ora_inizio, TIME_FORMAT((a.ora + INTERVAL 1 HOUR), '%H:%i') as ora_fine, a.stato 
                FROM appuntamento a 
                INNER JOIN utente u ON a.utente_id = u.id 
                WHERE a.personal_trainer_id = ? AND (a.stato = 'confermato' OR a.stato = 'cancellato')";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user = $stmt->get_result();
    };

    _header('Storico Allenamenti', 
    '<link rel="stylesheet" href="../css/utility.css">
    <script src="../js/storicoAllenamenti.js"></script>', $q);
    menuPT();
?>
<main>
    <section class="welcome-section">
        <h1>Storico Allenamenti</h1>

        <input type="text" id="filter" class="filter-input" placeholder="Filtra...">
        <button onclick="filtraOggi()" class="filter-button">Appuntamenti di Oggi</button>
        <button onclick="filtraTutti()" class="filter-button">Tutti gli Appuntamenti</button>

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
                    <tr id="appuntamento-<?php echo $row['id']; ?>" data-data="<?php echo $row['data']; ?>">
                        <td><?php echo htmlspecialchars($row['utente']); ?></td>
                        <td><?php echo htmlspecialchars($row['data']); ?></td>
                        <td><?php echo htmlspecialchars($row['ora_inizio']); ?></td>
                        <td><?php echo htmlspecialchars($row['ora_fine']); ?></td>
                        <td>
                            <?php 
                                if ($row['stato'] === 'confermato') {
                                    echo '<a style="font-weight: bold;" href="impostaObiettivi.php?appuntamento_id=' . $row['id'] . '">Imposta Obiettivi</a>';
                                } 
                                else {
                                    echo htmlspecialchars($row['stato']);
                                }
                            ?>
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