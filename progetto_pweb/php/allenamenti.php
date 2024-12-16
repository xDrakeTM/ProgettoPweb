<?php
    include '../utility/functions.php';

    controllaUtente('homePT');
    
    _header('I tuoi allenamenti', '<script src="../js/allenamenti.js"></script> 
    <link rel="stylesheet" href="../css/utility.css">');
    menuUtente();
?>

        <main>
            <section class="welcome-section">
                <h1>I tuoi allenamenti</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Ora Inizio</th>
                            <th>Ora Fine</th>
                            <th>Personal Trainer</th>
                            <th>Stato</th>
                        </tr>
                    </thead>
                    <tbody id="allenamentiTableBody">
                        <!-- Dati degli appuntamenti -->
                    </tbody>
                </table>
            </section>
        </main>

<?php
    _footer();
?>
