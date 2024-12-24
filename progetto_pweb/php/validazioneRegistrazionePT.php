<?php
    include '../utility/getDBConnection.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $conn = getDBConnection();

    if ($conn->connect_error) {
        die(json_encode(["success" => false, "message" => "Connessione fallita: " . $conn->connect_error]));
    }

    try {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nome = $_POST['nome'];
            $cognome = $_POST['cognome'];
            $email = $_POST['email'];
            $data_nascita = $_POST['data_nascita'];
            $genere = $_POST['genere'];
            $cellulare = $_POST['cellulare'];
            $risposta1 = $_POST['risposta1'];
            $risposta2 = $_POST['risposta2'];
            $password = $_POST['password'];
            $conf_password = $_POST['conf_password'];
            $attivo = false;

            if ($password !== $conf_password) {
                echo json_encode(["success" => false, "message" => "Le password non corrispondono."]);
                exit;
            }

            if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
                echo json_encode(["success" => false, "message" => "La password deve contenere almeno 8 caratteri, una lettera maiuscola, una minuscola, un numero e un carattere speciale."]);
                exit;
            }

            if (strlen($cellulare) !== 10) {
                echo json_encode(["success" => false, "message" => "Il numero di cellulare deve essere composto da 10 cifre."]);
                exit;
            }

            $stmt = $conn->prepare("SELECT id FROM personal_trainer WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                echo json_encode(["success" => false, "message" => "Email già in uso."]);
                exit;
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $hashed_risposta1 = password_hash($risposta1, PASSWORD_DEFAULT);
            $hashed_risposta2 = password_hash($risposta2, PASSWORD_DEFAULT);

            if (isset($_FILES['curriculum']) && $_FILES['curriculum']['error'] == UPLOAD_ERR_OK) {
                $curriculum = $_FILES['curriculum'];
                $fileSize = $curriculum['size'];
                $fileTmpPath = $curriculum['tmp_name'];
                $fileName = basename($curriculum['name']);
                $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if ($fileSize > 5242880) {
                    echo json_encode(["success" => false, "message" => "Il file è troppo grande. La dimensione massima è di 5 MB."]);
                    exit;
                }

                $allowedTypes = ['pdf', 'jpeg', 'png'];
                if (!in_array($fileType, $allowedTypes)) {
                    echo json_encode(["success" => false, "message" => "Tipo di file non consentito. Accettiamo solo PDF, JPG o PNG."]);
                    exit;
                }

                $uploadDir = __DIR__ . '/../curriculum/';
                $uniqueFileName = uniqid() . '_' . time() . '.' . $fileType;
                $destPath = $uploadDir . $uniqueFileName;
                if (!move_uploaded_file($fileTmpPath, $destPath)) {
                    echo json_encode(["success" => false, "message" => "Errore durante il caricamento del curriculum."]);
                    exit;
                }

            } 
            else {
                echo json_encode(["success" => false, "message" => "Curriculum mancante."]);
                exit;
            }

            $stmt = $conn->prepare("INSERT INTO personal_trainer (nome, cognome, email, data_nascita, genere, cellulare, password, curriculum, risposta1, risposta2, attivo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssi", $nome, $cognome, $email, $data_nascita, $genere, $cellulare, $hashed_password, $uniqueFileName, $hashed_risposta1, $hashed_risposta2, $attivo);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Registrazione completata con successo!"]);
            } 
            else {
                echo json_encode(["success" => false, "message" => "Errore durante la registrazione: " . $stmt->error]);
            }

            $stmt->close();
        }
    } 
    catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Errore: " . $e->getMessage()]);
    }

    $conn->close();
?>