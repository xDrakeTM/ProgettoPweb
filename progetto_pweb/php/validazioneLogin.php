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
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Verifica se l'utente è un utente normale
            $stmt = $conn->prepare("SELECT * FROM utente WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user["password"])) {
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["user_email"] = $user["email"];
                    $_SESSION["user_nome"] = $user["nome"];
                    $_SESSION["user_genere"] = $user["genere"];
                    $_SESSION["user_tipo"] = "utente";

                    $timestamp_login = date('Y-m-d H:i:s');
                    $stmt = $conn->prepare("UPDATE utente SET timestamp_login = ?, accessi = accessi + 1 WHERE email = ?");
                    $stmt->bind_param("ss", $timestamp_login, $email);
                    $stmt->execute();

                    echo json_encode([
                        "success" => true,
                        "message" => "Login utente riuscito!",
                        "user_tipo" => "utente"
                    ]);
                    exit();
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Password errata!"
                    ]);
                    exit();
                }
            }

            // Verifica se l'utente è un personal trainer
            $stmt = $conn->prepare("SELECT * FROM personal_trainer WHERE email = ? AND attivo = 1 LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $pt = $result->fetch_assoc();
                if (password_verify($password, $pt["password"])) {
                    $_SESSION["user_id"] = $pt["id"];
                    $_SESSION["user_email"] = $pt["email"];
                    $_SESSION["user_nome"] = $pt["nome"];
                    $_SESSION["user_genere"] = $pt["genere"];
                    $_SESSION["user_tipo"] = "personal_trainer";

                    $timestamp_login = date('Y-m-d H:i:s');
                    $stmt = $conn->prepare("UPDATE personal_trainer SET timestamp_login = ?, accessi = accessi + 1 WHERE email = ?");
                    $stmt->bind_param("ss", $timestamp_login, $email);
                    $stmt->execute();

                    echo json_encode([
                        "success" => true,
                        "message" => "Login personal trainer riuscito!",
                        "user_tipo" => "personal_trainer"
                    ]);
                    exit();
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Password errata!"
                    ]);
                    exit();
                }
            }

            // Verifica se l'utente è un amministratore
            $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $admin = $result->fetch_assoc();
                if (password_verify($password, $admin["password"])) {
                    $_SESSION["user_id"] = $admin["id"];
                    $_SESSION["user_email"] = $admin["email"];
                    $_SESSION["user_nome"] = $admin["nome"];
                    $_SESSION["user_tipo"] = "admin";

                    $timestamp_login = date('Y-m-d H:i:s');
                    $stmt = $conn->prepare("UPDATE admin SET timestamp_login = ? WHERE email = ?");
                    $stmt->bind_param("ss", $timestamp_login, $email);
                    $stmt->execute();

                    echo json_encode([
                        "success" => true,
                        "message" => "Login amministratore riuscito!",
                        "user_tipo" => "admin"
                    ]);
                    exit();
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Password errata!"
                    ]);
                    exit();
                }
            }

            // Se nessuna corrispondenza è trovata
            echo json_encode([
                "success" => false,
                "message" => "Utente non trovato!"
            ]);
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