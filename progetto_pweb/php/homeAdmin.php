<?php
    include '../utility/functions.php';

    session_start();
    // password: adminpassword

    controllaAdmin();

    _header('Home Admin');
    menuAdmin();
?>

<main>
    <section class="welcome-section">
        <h2>Benvenuto <?php echo htmlspecialchars($_SESSION['user_nome']); ?>!</h2>
        <p>Questa è la tua dashboard amministrativa. Qui puoi gestire utenti, personal trainer, contenuti e visualizzare le statistiche.</p>
    </section>

    <section class="features-section">
        <div class="feature-box">
            <h3>Gestione Utenti</h3>
            <p>Gestisci gli utenti registrati sulla piattaforma.</p>
            <a href="gestioneUtenti.php" class="button">Vai</a>
        </div>
        <div class="feature-box">
            <h3>Gestione Personal Trainer</h3>
            <p>Gestisci i personal trainer registrati sulla piattaforma.</p>
            <a href="gestionePT.php" class="button">Vai</a>
        </div>
        <div class="feature-box">
            <h3>Statistiche</h3>
            <p>Visualizza le statistiche della piattaforma.</p>
            <a href="statisticheAdmin.php" class="button">Vai</a>
        </div>
    </section>
</main>

<?php
    _footer();
?>