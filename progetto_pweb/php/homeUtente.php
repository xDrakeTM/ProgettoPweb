<?php
    require_once '../utility/functions.php';

    controllaUtente('homePT');
    _header('Home');
    menuUtente();
?>

        <main>
            <section class="welcome-section">
                <?php
                switch ($_SESSION["user_genere"]) {
                    case 'M':
                        echo "<h2>Benvenuto, " . htmlspecialchars($_SESSION['user_nome']) . "!</h2>"; 
                        break;

                    case 'F':
                        echo "<h2>Benvenuta, " . htmlspecialchars($_SESSION['user_nome']) . "!</h2>"; 
                        break;

                    case 'A':
                        echo "<h2>Benvenut*, " . htmlspecialchars($_SESSION['user_nome']) . "!</h2>"; 
                        break;
                }
                ?>
                <p>Esplora le nostre funzionalità e prenota il tuo allenamento!</p>
            </section>

            <section class="features-section">
                <div class="feature-box">
                    <h3>Prenota il tuo Allenamento</h3>
                    <p>Prenota il tuo allenamento tranquillamente online!</p>
                    <a href="prenotazione.php" class="button">Prenota</a>
                </div>
                <div class="feature-box">
                    <h3>Controlla i tuoi Appuntamenti</h3>
                    <p>Visualizza lo stato del tuo appuntamento!</p>
                    <a href="allenamenti.php" class="button">Vai</a>
                </div>
                <div class="feature-box">
                    <h3>I tuoi Obiettivi</h3>
                    <p>Compila i tuoi obiettivi raggiunti in allenamento!</p>
                    <a href="obiettivi.php" class="button">Vai</a>
                </div>
                <div class="feature-box">
                    <h3>Il tuo Profilo</h3>
                    <p>Gestisci le tue informazioni personali.</p>
                    <a href="profiloUtente.php" class="button">Vai al Profilo</a>
                </div>
                <div class="feature-box">
                    <h3>Le tue Statistiche</h3>
                    <p>Vedi le tue statistiche.</p>
                    <a href="statistiche.php" class="button">Vai</a>
                </div>
            </section>
        </main>
<?php
    _footer();
?>