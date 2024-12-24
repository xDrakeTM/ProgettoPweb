<?php 
    require_once '../utility/functions.php';

    controllaPT('homeUtente');
    _header('Home PT');
    menuPT();
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
                <p>Amministra qui i clienti che si sono affidati a te!</p>
            </section>

            <section class="features-section">
                <div class="feature-box">
                    <h3>Gestisci Allenamenti</h3>
                    <p>Gestisci gli allenamenti dei tuo assistiti!</p>
                    <a href="gestisciAllenamenti.php" class="button">Vai</a>
                </div>
                <div class="feature-box">
                    <h3>Il tuo Profilo</h3>
                    <p>Gestisci le tue informazioni personali.</p>
                    <a href="profiloPT.php" class="button">Vai al Profilo</a>
                </div>
                <div class="feature-box">
                    <h3>Gestisci gli Allenamenti</h3>
                    <p>Gestisci le richieste di allenamento.</p>
                    <a href="gestisciAllenamenti.php" class="button">Vai</a>
                </div>
                <div class="feature-box">
                    <h3>Storico Allenamenti</h3>
                    <p>Controlla e gestisci tutti i tuoi allenamenti.</p>
                    <a href="storicoAllenamenti.php" class="button">Vai</a>
                </div>
            </section>
        </main>

<?php 
    _footer();
?>