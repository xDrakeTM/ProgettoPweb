<?php
    include '../utility/getDBConnection.php';

    $conn = getDBConnection();

    if ($conn->connect_error) {
        die(json_encode([
            "success" => false,
            "message" => "Connessione al database fallita: " . $conn->connect_error
        ]));
    }

    try {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $risposta1 = $_POST['risposta1'];
            $risposta2 = $_POST['risposta2'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            $tipo_utente = $_POST['tipo_utente'];
            $tabella = $tipo_utente === 'utente' ? 'utente' : 'personal_trainer';

            if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_password)) {
                echo json_encode([
                    "success" => false,
                    "message" => "La password deve essere di almeno 8 caratteri e contenere almeno una lettera maiuscola, una lettera minuscola, un numero e un carattere speciale."
                ]);
                exit;
            }

            if ($new_password !== $confirm_password) {
                echo json_encode([
                    "success" => false,
                    "message" => "Le password non coincidono."
                ]);
                exit;
            }

            $q = "SELECT risposta1, risposta2 FROM $tabella WHERE email = ?";
            $stmt = $conn->prepare($q);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                echo json_encode([
                    "success" => false,
                    "message" => "Account non trovato."
                ]);
                exit;
            }

            $utente = $result->fetch_assoc();

            if (!password_verify($risposta1, $utente['risposta1']) || !password_verify($risposta2, $utente['risposta2'])) {
                echo json_encode([
                    "success" => false,
                    "message" => "Risposte alle domande di sicurezza errate. ". $utente['risposta2']
                ]);
                exit;
            }

            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE $tabella SET password = ? WHERE email = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ss", $hashed_password, $email);

            if ($update_stmt->execute()) {
                echo json_encode([
                    "success" => true,
                    "message" => "Password aggiornata con successo."
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Errore durante l'aggiornamento della password."
                ]);
            }
        }
    } 
    catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Errore: " . $e->getMessage()
        ]);
    }

    $conn->close();
?>