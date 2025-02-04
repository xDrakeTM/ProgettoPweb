<?php
    // Include il file delle funzioni utilitarie
    require_once '../utility/functions.php';
    // Include il file per ottenere la connessione al database
    require_once '../utility/getDBConnection.php';

    // Controlla se l'utente ha i permessi per accedere a questa pagina
    controllaUtente('homePT');

    // Avvia la sessione se non è già stata avviata
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Recupera l'ID dell'utente dalla sessione
    $user_id = $_SESSION["user_id"];

    // Verifica se l'ID dell'allenamento è stato specificato
    if (!isset($_GET['allenamento_id'])) {
        die("ID allenamento non specificato.");
    }

    // Recupera l'ID dell'allenamento dai parametri GET
    $allenamento_id = $_GET['allenamento_id'];

    // Ottiene la connessione al database
    $conn = getDBConnection();
    // Verifica se ci sono errori di connessione
    if ($conn->connect_error) {
        die("Errore di connessione al database: " . $conn->connect_error);
    }

    // Prepara la query SQL per selezionare gli obiettivi dell'allenamento
    $sql = "SELECT o.id, o.tipo_obiettivo, o.obiettivo, o.descrizione, o.progresso1, o.progresso2, o.progresso3, o.ripetizioni, o.serie, o.peso
            FROM obiettivi o 
            WHERE o.appuntamento_id = ? AND (o.progresso1 IS NULL OR o.progresso2 IS NULL OR o.progresso3 IS NULL)";
    $stmt = $conn->prepare($sql);
    // Associa l'ID dell'allenamento al parametro della query
    $stmt->bind_param("i", $allenamento_id);
    // Esegue la query
    $stmt->execute();
    // Ottiene il risultato della query
    $result = $stmt->get_result();
    // Inizializza un array per memorizzare gli obiettivi
    $obiettivi = [];
    // Itera sui risultati della query e li aggiunge all'array degli obiettivi
    while ($row = $result->fetch_assoc()) {
        $obiettivi[] = $row;
    }
    // Chiude lo statement
    $stmt->close();
    // Chiude la connessione al database
    $conn->close();

    // Include l'header della pagina con il titolo e i link ai file CSS e JS
    _header('Compila Obiettivi', 
    '<link rel="stylesheet" href="../css/obiettivi.css">
    <script src="../js/compilaObiettivi.js"></script>');
    // Include il menu dell'utente
    menuUtente();
?>
<main>
    <section class="welcome-section">
        <h1>Compila i tuoi Obiettivi</h1>
        <?php if (empty($obiettivi)) : ?>
            <p style="margin-top: 20px;">Non ci sono obiettivi da compilare</p>
        <?php else : ?>
            <form id="compilaObiettiviForm">
                <?php foreach ($obiettivi as $index => $obiettivo) : ?>
                    <?php if ($index > 0) : ?>
                        <hr>
                    <?php endif; ?>
                    <div class="obiettivo" id="obiettivo-<?php echo $obiettivo['id']; ?>">
                        <h2 style="margin-bottom: 10px; color: black;"><?php echo htmlspecialchars($obiettivo['obiettivo']); ?></h2>
                        <p style="margin-bottom: 20px;"><?php echo htmlspecialchars($obiettivo['descrizione']); ?></p>
                        <?php if ($obiettivo['tipo_obiettivo'] === 'quantitativo') : ?>
                            <div class="input-group">
                                <div>
                                    <label for="progresso1-<?php echo $obiettivo['id']; ?>">Ripetizioni:</label>
                                    <input type="number" id="progresso1-<?php echo $obiettivo['id']; ?>" name="progresso1[<?php echo $obiettivo['id']; ?>]" value="<?php echo htmlspecialchars($obiettivo['progresso1']); ?>" min="0" max="<?php echo htmlspecialchars($obiettivo['ripetizioni']); ?>">
                                    <span>/</span>
                                    <span class="pt-value"><?php echo htmlspecialchars($obiettivo['ripetizioni']); ?></span>
                                </div>
                                <div>
                                    <label for="progresso2-<?php echo $obiettivo['id']; ?>">Serie:</label>
                                    <input type="number" id="progresso2-<?php echo $obiettivo['id']; ?>" name="progresso2[<?php echo $obiettivo['id']; ?>]" value="<?php echo htmlspecialchars($obiettivo['progresso2']); ?>" min="0" max="<?php echo htmlspecialchars($obiettivo['serie']); ?>">
                                    <span>/</span>
                                    <span class="pt-value"><?php echo htmlspecialchars($obiettivo['serie']); ?></span>
                                </div>
                                <div>
                                    <label for="progresso3-<?php echo $obiettivo['id']; ?>">Peso (kg):</label>
                                    <input type="number" id="progresso3-<?php echo $obiettivo['id']; ?>" name="progresso3[<?php echo $obiettivo['id']; ?>]" value="<?php echo htmlspecialchars($obiettivo['progresso3']); ?>" step="0.01" min="0" max="<?php echo htmlspecialchars($obiettivo['peso']); ?>">
                                    <span>/</span>
                                    <span class="pt-value"><?php echo htmlspecialchars($obiettivo['peso']); ?></span>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="input-group">
                                <div>
                                    <label for="progresso2-<?php echo $obiettivo['id']; ?>">Progresso:</label>
                                    <input type="number" id="progresso2-<?php echo $obiettivo['id']; ?>" name="progresso2[<?php echo $obiettivo['id']; ?>]" value="<?php echo htmlspecialchars($obiettivo['progresso2']); ?>" min="0" max="<?php echo htmlspecialchars($obiettivo['progresso1']); ?>">
                                    <span>/</span>
                                    <span class="pt-value"><?php echo htmlspecialchars($obiettivo['progresso1']); ?></span>
                                    <input type="hidden" id="progresso1-<?php echo $obiettivo['id']; ?>" value="<?php echo htmlspecialchars($obiettivo['progresso1']); ?>">
                                    <input type="hidden" id="progresso3-<?php echo $obiettivo['id']; ?>" value="-1">
                                </div>
                            </div>
                        <?php endif; ?>
                        <button class="salvaObiettivo" type="button" onclick="salvaProgresso(<?php echo $obiettivo['id']; ?>)">Salva Progresso</button>
                    </div>
                <?php endforeach; ?>
            </form>
        <?php endif; ?>
    </section>
</main>
<?php
    _footer();
?>