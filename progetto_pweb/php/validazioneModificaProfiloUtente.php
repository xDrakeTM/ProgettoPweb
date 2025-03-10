<?php
    require_once '../utility/getDBConnection.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["user_id"])) {
        echo json_encode(["success" => false, "message" => "Utente non autenticato."]);
        exit();
    }

    $conn = getDBConnection();

    if ($conn->connect_error) {
        echo json_encode(["success" => false, "message" => "Connessione fallita: " . $conn->connect_error]);
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION['user_id'];
        $email = $_POST['email'];
        $altezza = $_POST['altezza'];
        $peso = $_POST['peso'];
        $data_emissione_certificato = $_POST['data_emissione_certificato'];
        $informazioni_mediche = $_POST['informazioni_mediche'];
        $note = $_POST['note'];

        if (isset($_FILES['certificato']) && $_FILES['certificato']['error'] == UPLOAD_ERR_OK) {
            $certificato_medico = $_FILES['certificato'];
            $fileSize = $certificato_medico['size'];
            $fileTmpPath = $certificato_medico['tmp_name'];
            $fileName = basename($certificato_medico['name']);
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Verifica se il file è troppo grande
            if ($fileSize > 5242880) {
                echo json_encode(["success" => false, "message" => "Il file è troppo grande. La dimensione massima è di 5 MB."]);
                exit();
            }

            // Verifica se il tipo di file è consentito
            $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png'];
            if (!in_array($fileType, $allowedTypes)) {
                echo json_encode(["success" => false, "message" => "Tipo di file non consentito. Accettiamo solo PDF, JPG o PNG."]);
                exit();
            }

            // Imposta la directory di upload e il nome unico del file
            $uploadDir = __DIR__ . '/../certificati/';
            $uniqueFileName = uniqid() . '_' . time() . '.' . $fileType;
            $destPath = $uploadDir . $uniqueFileName;

            // Sposta il file caricato nella directory di destinazione
            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                echo json_encode(["success" => false, "message" => "Errore durante il caricamento del certificato medico."]);
                exit();
            }

            // Elimina il vecchio certificato
            $stmt = $conn->prepare("SELECT certificato FROM utente WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $old_certificato = $result->fetch_assoc()['certificato'];
            $stmt->close();

            if ($old_certificato && file_exists($uploadDir . $old_certificato)) {
                unlink($uploadDir . $old_certificato);
            }

            // Prepara la query SQL per aggiornare il profilo dell'utente con il nuovo certificato
            $stmt = $conn->prepare("UPDATE utente SET email = ?, altezza = ?, peso = ?, data_emissione_certificato = ?, informazioni_mediche = ?, note = ?, certificato = ? WHERE id = ?");
            $stmt->bind_param("sssssssi", $email, $altezza, $peso, $data_emissione_certificato, $informazioni_mediche, $note, $uniqueFileName, $user_id);
        } 
        else {
            // Prepara la query SQL per aggiornare il profilo dell'utente senza il nuovo certificato
            $stmt = $conn->prepare("UPDATE utente SET email = ?, altezza = ?, peso = ?, data_emissione_certificato = ?, informazioni_mediche = ?, note = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $email, $altezza, $peso, $data_emissione_certificato, $informazioni_mediche, $note, $user_id);
        }

        // Esegue la query e verifica se è stata eseguita con successo
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Profilo aggiornato con successo"]);
        } 
        else {
            echo json_encode(["success" => false, "message" => "Errore durante l'aggiornamento del profilo: " . $stmt->error]);
        }

        // Chiude lo statement
        $stmt->close();
    }

    // Chiude la connessione al database
    $conn->close();
?>