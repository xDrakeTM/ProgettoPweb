<?php    
    function _header($titolo, $frammento = '', $sql = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["user_id"])) {
            header("Location: ../php/login.php");
            exit();
        }

        if (is_callable($sql)) {
            global $user;
            $sql();
        }


        echo '
            <!DOCTYPE html>
            <html lang="it">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>' . $titolo . ' - PulseCoach</title>
                <link rel="icon" type="image/png" href="../immagini/icona_schede.jpg">
                <link rel="stylesheet" href="../css/home.css">
                ' . $frammento . '
            </head>';
    }

    function menuUtente() {
        echo '<body>
                <div class="layout">
                    <header>
                        <div class="navbar">
                            <h1>PulseCoach</h1>
                            <nav>
                                <ul>
                                    <li><a href="homeUtente.php">Dashboard</a></li>
                                    <li><a href="profiloUtente.php">Profilo</a></li>
                                    <li><a href="prenotazione.php">Prenota</a></li>
                                    <li><a href="obiettivi.php">Obiettivi</a></li>
                                    <li><a href="statistiche.php">Statistiche Mensili</a></li>
                                    <li><a href="logout.php">Logout</a></li>
                                </ul>
                            </nav>
                        </div>
                    </header>';
    }

    function menuPT() {
        echo '<body>
                <div class="layout">
                    <header>
                        <div class="navbar">
                            <h1>PulseCoach</h1>
                            <nav>
                                <ul>
                                    <li><a href="homePT.php">Dashboard</a></li>
                                    <li><a href="profiloPT.php">Profilo</a></li>
                                    <li><a href="gestisciAllenamenti.php">Gestisci Allenamenti</a></li>
                                    <li><a href="storicoAllenamenti.php">Storico Allenamenti</a></li>
                                    <li><a href="logout.php">Logout</a></li>
                                </ul>
                            </nav>
                        </div>
                    </header>';
    }

    function menuAdmin() {
        echo '<body>
                <div class="layout">
                    <header>
                        <div class="navbar">
                            <h1>PulseCoach</h1>
                            <nav>
                                <ul>
                                    <li><a href="homeAdmin.php">Dashboard</a></li>
                                    <li><a href="gestioneUtenti.php">Gestione Utenti</a></li>
                                    <li><a href="gestionePT.php">Gestione Personal Trainer</a></li>
                                    <li><a href="statisticheAdmin.php">Statistiche</a></li>
                                    <li><a href="logout.php">Logout</a></li>
                                </ul>
                            </nav>
                        </div>
                    </header>';
    }

    function _footer() {
        echo ' 
                </div>

            <footer>
                <p>&copy; 2025 PulseCoach.</p>
            </footer>
        </body>
        </html>';
    }

    session_start();

    function controllaUtente($paginaPrincipale) {
        if (!isset($_SESSION['user_tipo'])) {
            header("Location: login.php");
            exit();
        }

        if ($_SESSION['user_tipo'] !== 'utente') {
            header("Location: $paginaPrincipale.php");
            exit();
        }
    }

    function controllaPT($paginaPrincipale) {
        if (!isset($_SESSION['user_tipo'])) {
            header("Location: login.php");
            exit();
        }

        if ($_SESSION['user_tipo'] !== 'personal_trainer') {
            header("Location: $paginaPrincipale.php");
            exit();
        }
    }

    function controllaAdmin() {
        if (!isset($_SESSION['user_tipo'])) {
            header("Location: login.php");
            exit();
        }

        if ($_SESSION['user_tipo'] !== 'admin') {
            session_destroy();
            header("Location: login.php");
            exit();
        }
    }
?>