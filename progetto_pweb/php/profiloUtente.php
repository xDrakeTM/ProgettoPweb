<?php
    require_once '../utility/functions.php';
    require_once '../utility/getDBConnection.php';

    controllaUtente('homePT');

    $user_id = $_SESSION["user_id"];

    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT nome, cognome, email, data_nascita, genere, altezza, peso, informazioni_mediche, note, certificato, data_emissione_certificato FROM utente WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    _header('Profilo Utente', '
    <link rel="stylesheet" href="../css/profilo.css">
    <script src="../js/profilo.js"></script>');
    menuUtente();
?>
<main>
    <section class="welcome-section">
        <h1>Modifica il tuo Profilo</h1>

        <form id="updateProfileForm" onsubmit="validaModificaProfiloUtente(event)">
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

            <label for="altezza">Altezza (cm):</label>
            <input type="number" id="altezza" name="altezza" value="<?php echo htmlspecialchars($user['altezza']); ?>" required>

            <label for="peso">Peso (kg):</label>
            <input type="number" id="peso" name="peso" step="0.01" value="<?php echo $user['peso']; ?>" required>

            <label for="certificato">Certificato medico (PDF, JPG, PNG):</label>
            <input type="file" id="certificato" name="certificato" accept=".pdf,.jpg,.jpeg,.png">
            <p style="margin-bottom: 20px;">Certificato attuale: <a href="../certificati/<?php echo htmlspecialchars($user['certificato']); ?>">Visualizza</a></p>

            <label for="data_emissione_certificato">Data di Emissione del Certificato:</label>
            <input type="date" id="data_emissione_certificato" name="data_emissione_certificato" value="<?php echo htmlspecialchars($user['data_emissione_certificato']); ?>" required>

            <label for="informazioni_mediche">Informazioni Mediche:</label>
            <textarea id="informazioni_mediche" name="informazioni_mediche" rows="3"><?php echo htmlspecialchars($user['informazioni_mediche']); ?></textarea>

            <label for="note">Note:</label>
            <textarea id="note" name="note" rows="3"><?php echo htmlspecialchars($user['note']); ?></textarea>

            <button id="butUpdate" type="submit">Aggiorna</button>
        </form>

        <button id="modifyPasswordButton" style="background-color: orange;" onclick="window.location.href='../html/modificaPassword.html'">Modifica Password</button>
        <button id="deleteAccountButton" style="background-color: red;" onclick="eliminaAccount()">Elimina Account</button>

        <div id="status"></div>
    </section>
</main>
<?php
    _footer();
?>