<?php
    include '../utility/functions.php';

    $conn = new mysqli("localhost", "root", "", "carinci_635710");
    if ($conn->connect_error) {
        die(json_encode(["success" => false, "message" => "Connessione al database fallita: " . $conn->connect_error]));
    }

    $sql = "SELECT id, nome, cognome, curriculum FROM personal_trainer";
    $result = $conn->query($sql);

    $personalTrainers = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $personalTrainers[] = [
                "id" => $row["id"],
                "nome" => $row["nome"],
                "cognome" => $row["cognome"],
                "curriculum" => $row["curriculum"]
            ];
        }
    }

    $conn->close();

    echo json_encode($personalTrainers);
?>