<?php
    include '../utility/functions.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    controllaAdmin();

    $conn = new mysqli("localhost", "root", "", "carinci_635710");
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM utente";
    $result = $conn->query($sql);
    $conn->close();

    _header('Gestione Utenti', 
    '<link rel="stylesheet" href="../css/utility.css">
    <script src="../js/gestioneUtentiPT.js"></script>');
    menuAdmin();
?>

<main>
    <section class="welcome-section">
        <h2>Gestione Utenti</h2>
    </section>

    <section class="filter-section">
        <input type="text" class="filter-input" id="filter-input" placeholder="Filtra...">
    </section>

    <section class="users-section">
        <table style="font-size: 10px;">
            <thead>
                <tr>
                    <th>Utente</th>
                    <th>Email</th>
                    <th>Data di Nascita</th>
                    <th>Genere</th>
                    <th>Altezza</th>
                    <th>Peso</th>
                    <th>Informazioni Mediche</th>
                    <th>Note</th>
                    <th>Certificato</th>
                    <th>Data Em. Certif.</th>
                    <th>Validità Certif.</th>
                    <th>Timestamp Login</th>
                    <th>Timestamp Logout</th>
                    <th>Timestamp Creazione Account</th>
                    <th>Timestamp Aggiornamento</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody id="TableBody">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <?php
                            $data_emissione = new DateTime($row['data_emissione_certificato']);
                            $data_scadenza = (clone $data_emissione)->modify('+12 months');
                            $oggi = new DateTime();
                            $stato_certificato = '';
                            $colore = '';

                            if ($oggi > $data_scadenza) {
                                $stato_certificato = 'Scaduto';
                                $colore = 'red';
                            } 
                            elseif ($oggi > $data_scadenza->modify('-1 month')) {
                                $stato_certificato = 'Sta per scadere';
                                $colore = 'orange';
                            } 
                            else {
                                $stato_certificato = 'Ok';
                                $colore = 'green';
                            }
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nome']) . ' ' . htmlspecialchars($row['cognome']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['data_nascita']); ?></td>
                            <td><?php echo htmlspecialchars($row['genere']); ?></td>
                            <td><?php echo htmlspecialchars($row['altezza']); ?></td>
                            <td><?php echo htmlspecialchars($row['peso']); ?></td>
                            <td><?php echo htmlspecialchars($row['informazioni_mediche']); ?></td>
                            <td><?php echo htmlspecialchars($row['note']); ?></td>
                            <td><a href="../certificati/<?php echo htmlspecialchars($row['certificato']); ?>">Visualizza</a></td>
                            <td><?php echo htmlspecialchars($row['data_emissione_certificato']); ?></td>
                            <td style="font-weight: bold; color: <?php echo $colore; ?>;"><?php echo $stato_certificato; ?></td>
                            <td><?php echo htmlspecialchars($row['timestamp_login']); ?></td>
                            <td><?php echo htmlspecialchars($row['timestamp_logout']); ?></td>
                            <td><?php echo htmlspecialchars($row['timestamp_creazione']); ?></td>
                            <td><?php echo htmlspecialchars($row['timestamp_aggiornamento']); ?></td>
                            <td>
                                <button onclick="eliminaAccount(<?php echo $row['id']; ?>)">Elimina</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="16">Nessun utente trovato.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

<?php
    _footer();
?>