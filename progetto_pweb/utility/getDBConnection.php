<?php
    function getDbConnection() {
        $conn = new mysqli("localhost", "root", "", "carinci_635710");

        return $conn;
    }
?>