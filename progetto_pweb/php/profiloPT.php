<?php
    include '../utility/functions.php';

    controllaPT('homeUtente');

    $sql = function() use ($conn) {
        global $user;

        $user_id = $_SESSION["user_id"];

        $conn = new mysqli("localhost", "root", "", "carinci_635710");
        $stmt = $conn->prepare("SELECT nome, cognome, email, data_nascita, genere, cellulare, curriculum FROM personal_trainer WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    };

    _header('Profilo Personal Trainer', '
    <link rel="stylesheet" href="../css/profilo.css">
    <script src="../js/profilo.js"></script>', $sql);
    menuPT();
?>
        <main>
            <section class="welcome-section">
                <h1>Modifica il tuo Profilo</h1>

                <form id="updateProfileForm" onsubmit="validaModificaProfiloPT(event)">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" readonly disabled>

                    <label for="cognome">Cognome:</label>
                    <input type="text" id="cognome" name="cognome" value="<?php echo htmlspecialchars($user['cognome']); ?>" readonly disabled>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                    <label for="data_nascita">Data di Nascita:</label>
                    <input type="date" id="data_nascita" name="data_nascita" value="<?php echo $user['data_nascita']; ?>" readonly disabled>

                    <label for="genere">Genere:</label>
                    <input type="text" id="genere" name="genere" value="<?php 
                        switch ($_SESSION["user_genere"]) {
                            case 'M':
                                echo "Maschio"; 
                                break;

                            case 'F':
                                echo "Femmina"; 
                                break;

                            case 'A':
                                echo "Altro"; 
                                break;
                        }
                    ?>" readonly disabled>

                    <label for="cellulare">Cellulare:</label>
                    <input type="tel" id="cellulare" name="cellulare" value="<?php echo $user['cellulare']; ?>" required>

                    <label for="curriculum">Curriculum (PDF, DOC, DOCX):</label>
                    <input type="file" id="curriculum" name="curriculum" accept=".pdf,.doc,.docx">
                    <p style="margin-bottom: 20px;">Curriculum attuale: <a href="../curriculum/<?php echo htmlspecialchars($user['curriculum']); ?>">Visualizza</a></p>

                    <button type="submit">Aggiorna Profilo</button>
                </form>

                <button id="modifyPasswordButton" style="background-color: orange;" onclick="window.location.href='../html/modificaPassword.html'">Modifica Password</button>
                <button id="deleteAccountButton" style="background-color: red;" onclick="eliminaAccount()">Elimina Account</button>

                <div id="status"></div>
            </section>
        </main>
<?php
    _footer();
?>