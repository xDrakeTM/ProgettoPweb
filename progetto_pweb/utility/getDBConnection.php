<?php
    function getDbConnection() {
        $conn = new mysqli("localhost", "root", "", "carinci_635710");

        if ($conn->connect_error) {
            die("Connessione al database fallita: " . $conn->connect_error);
        }

        return $conn;
    }
?>