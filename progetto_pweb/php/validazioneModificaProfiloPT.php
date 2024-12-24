<?php
    require_once '../utility/getDBConnection.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["user_id"])) {
        header("Location: ../php/login.php");
        exit();
    }

    $conn = getDBConnection();

    if ($conn->connect_error) {
        die(json_encode(["success" => false, "message" => "Connessione fallita: " . $conn->connect_error]));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION['user_id'];
        $email = $_POST['email'];
        $cellulare = $_POST['cellulare'];

        if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] == UPLOAD_ERR_OK) {
            $curriculum = $_FILES['curriculum'];
            $fileSize = $curriculum['size'];
            $fileTmpPath = $curriculum['tmp_name'];
            $fileName = basename($curriculum['name']);
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if ($fileSize > 5242880) {
                echo json_encode(["success" => false, "message" => "Il file è troppo grande. La dimensione massima è di 5 MB."]);
                exit();
            }

            $allowedTypes = ['pdf', 'doc', 'docx'];
            if (!in_array($fileType, $allowedTypes)) {
                echo json_encode(["success" => false, "message" => "Tipo di file non consentito. Accettiamo solo PDF, DOC o DOCX."]);
                exit();
            }

            $uploadDir = __DIR__ . '/../curriculum/';
            $destPath = $uploadDir . $fileName;
            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                echo json_encode(["success" => false, "message" => "Errore durante il caricamento del curriculum."]);
                exit();
            }

            $stmt = $conn->prepare("SELECT curriculum FROM personal_trainer WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $old_curriculum = $result->fetch_assoc()['curriculum'];
            $stmt->close();

            if ($old_curriculum && file_exists($uploadDir . $old_curriculum)) {
                unlink($uploadDir . $old_curriculum);
            }

            $stmt = $conn->prepare("UPDATE personal_trainer SET email = ?, cellulare = ?, curriculum = ? WHERE id = ?");
            $stmt->bind_param("sssi", $email, $cellulare, $fileName, $user_id);
        } 
        else {
            $stmt = $conn->prepare("UPDATE personal_trainer SET email = ?, cellulare = ? WHERE id = ?");
            $stmt->bind_param("ssi", $email, $cellulare, $user_id);
        }

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Profilo aggiornato con successo!"]);
        } 
        else {
            echo json_encode(["success" => false, "message" => "Errore durante l'aggiornamento del profilo: " . $stmt->error]);
        }

        $stmt->close();
    }

    $conn->close();
?>