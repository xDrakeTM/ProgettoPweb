<?php
    include '../utility/functions.php';

    controllaPT('homeUtente');

    if (!isset($_GET['appuntamento_id'])) {
        die("ID appuntamento non specificato.");
    }

    $appuntamento_id = $_GET['appuntamento_id'];

    _header('Imposta Obiettivi', 
    '<link rel="stylesheet" href="../css/obiettivi.css">
     <script src="../js/impostaObiettivi.js"></script>');
    menuPT();
?>
<main>
    <section class="welcome-section">
        <h1>Imposta Obiettivi</h1>
        <div id="selezioneObiettivo">
            <button class="aggiungi" onclick="mostraForm('quantitativo')">Aggiungi Obiettivo Quantitativo</button>
            <button class="aggiungi" onclick="mostraForm('continuativo')">Aggiungi Obiettivo Continuativo</button>
        </div>
        <div id="altriObiettivi"></div>
    </section>
</main>

<?php
    _footer();
?>