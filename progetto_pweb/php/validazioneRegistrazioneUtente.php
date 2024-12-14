<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $conn = new mysqli("localhost", "root", "", "carinci_635710");

    if ($conn->connect_error) {
        die(json_encode([
            "success" => false,
            "message" => "Connessione al database fallita: " . $conn->connect_error
        ]));
    }

    try {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nome = $_POST['nome'];
            $cognome = $_POST['cognome'];
            $email = $_POST['email'];
            $data_nascita = $_POST['data_nascita'];
            $password = $_POST['password'];
            $conf_password = $_POST['conf_password'];
            $risposta1 = $_POST['risposta1'];
            $risposta2 = $_POST['risposta2'];
            $genere = $_POST['genere'];
            $altezza = $_POST['altezza'];
            $peso = $_POST['peso'];
            $data_emissione_certificato = $_POST['data_emissione_certificato'];
            $informazioni_mediche = $_POST['informazioni_mediche'];
            $note = $_POST['note'];

            if ($password !== $conf_password) {
                echo json_encode([
                    "success" => false,
                    "message" => "Le password non corrispondono."
                ]);
                exit;
            }

            if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
                echo json_encode([
                    "success" => false,
                    "message" => "La password deve contenere almeno 8 caratteri, una lettera maiuscola, una minuscola, un numero e un carattere speciale."
                ]);
                exit;
            }

            if (isset($_FILES['certificato_medico']) && $_FILES['certificato_medico']['error'] == UPLOAD_ERR_OK) {
                $certificato = $_FILES['certificato_medico'];
                $fileSize = $certificato['size'];
                $fileTmpPath = $certificato['tmp_name'];
                $fileName = basename($certificato['name']);
                $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if ($fileSize > 5242880) {
                    echo json_encode([
                        "success" => false,
                        "message" => "Il file è troppo grande. La dimensione massima è di 5 MB."
                    ]);
                    exit;
                }

                $allowedTypes = ['pdf', 'jpeg', 'png'];
                if (!in_array($fileType, $allowedTypes)) {
                    echo json_encode([
                        "success" => false,
                        "message" => "Tipo di file non consentito. Accettiamo solo PDF, JPG o PNG."
                    ]);
                    exit;
                }

                $uploadDir = __DIR__ . '/../certificati/';
                $destPath = $uploadDir . $fileName;
                if (!move_uploaded_file($fileTmpPath, $destPath)) {
                    echo json_encode([
                        "success" => false,
                        "message" => "Errore durante il caricamento del certificato medico."
                    ]);
                    exit;
                }
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Certificato medico mancante."
                ]);
                exit;
            }

            $stmt = $conn->prepare("SELECT id FROM utente WHERE email = ?");
            if (!$stmt) {
                echo json_encode([
                    "success" => false,
                    "message" => "Errore del server."
                ]);
                exit;
            }
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                echo json_encode([
                    "success" => false,
                    "message" => "Email già in uso."
                ]);
                exit;
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $hashed_risposta1 = password_hash($risposta1, PASSWORD_DEFAULT);
            $hashed_risposta2 = password_hash($risposta2, PASSWORD_DEFAULT);

            $insert = "INSERT INTO utente (nome, cognome, email, data_nascita, password, risposta1, risposta2, genere, altezza, peso, informazioni_mediche, note, certificato, data_emissione_certificato) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert);
            $stmt->bind_param("ssssssssddssss", $nome, $cognome, $email, $data_nascita, $hashed_password, $hashed_risposta1, $hashed_risposta2, $genere, $altezza, $peso, $informazioni_mediche, $note, $fileName, $data_emissione_certificato);

            if ($stmt->execute()) {
                echo json_encode([
                    "success" => true,
                    "message" => "Registrazione avvenuta con successo!"
                ]);
            } 
            else {
                echo json_encode([
                    "success" => false,
                    "message" => "Errore durante la registrazione."
                ]);
            }
        }
    } 
    catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Errore durante la registrazione: " . $e->getMessage()
        ]);
    }

    $conn->close();
?>