<?php
    // Include il file per ottenere la connessione al database
    require_once '../utility/getDBConnection.php';

    // Avvia la sessione se non è già stata avviata
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verifica se l'utente è autenticato
    if (!isset($_SESSION["user_id"])) {
        header("Location: ../php/login.php");
        exit();
    }

    // Ottiene la connessione al database
    $conn = getDBConnection();

    // Verifica se ci sono errori di connessione
    if ($conn->connect_error) {
        die(json_encode(["success" => false, "message" => "Connessione fallita: " . $conn->connect_error]));
    }

    // Verifica se la richiesta è di tipo POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION['user_id'];
        $email = $_POST['email'];
        $cellulare = $_POST['cellulare'];

        // Verifica se è stato caricato un file curriculum
        if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] == UPLOAD_ERR_OK) {
            $curriculum = $_FILES['curriculum'];
            $fileSize = $curriculum['size'];
            $fileTmpPath = $curriculum['tmp_name'];
            $fileName = basename($curriculum['name']);
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Verifica se il file è troppo grande
            if ($fileSize > 5242880) {
                echo json_encode(["success" => false, "message" => "Il file è troppo grande. La dimensione massima è di 5 MB."]);
                exit();
            }

            // Verifica se il tipo di file è consentito
            $allowedTypes = ['pdf', 'doc', 'docx'];
            if (!in_array($fileType, $allowedTypes)) {
                echo json_encode(["success" => false, "message" => "Tipo di file non consentito. Accettiamo solo PDF, DOC o DOCX."]);
                exit();
            }

            // Genera un nuovo nome per il file e sposta il file caricato nella directory dei curriculum
            $newFileName = uniqid() . '.' . $fileType;
            $uploadFileDir = '../curriculum/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Prepara la query SQL per aggiornare il profilo del personal trainer con il nuovo curriculum
                $stmt = $conn->prepare("UPDATE personal_trainer SET email = ?, cellulare = ?, curriculum = ? WHERE id = ?");
                $stmt->bind_param("sssi", $email, $cellulare, $newFileName, $user_id);
            } else {
                echo json_encode(["success" => false, "message" => "Errore durante il caricamento del file."]);
                exit();
            }
        } else {
            // Prepara la query SQL per aggiornare il profilo del personal trainer senza il curriculum
            $stmt = $conn->prepare("UPDATE personal_trainer SET email = ?, cellulare = ? WHERE id = ?");
            $stmt->bind_param("ssi", $email, $cellulare, $user_id);
        }

        // Esegue la query e verifica se è stata eseguita con successo
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Profilo aggiornato con successo."]);
        } else {
            echo json_encode(["success" => false, "message" => "Errore durante l'aggiornamento del profilo: " . $stmt->error]);
        }

        // Chiude lo statement
        $stmt->close();
    }

    // Chiude la connessione al database
    $conn->close();
?>