<?php

    function consultarBBDD($sql) {
        $host = "localhost";
        $user = "root";
        $password = "";
        $dbname = "trenfe";

        $conn = new mysqli($host, $user, $password, $dbname);

        if ($conn->connect_error) {
            return false;
        }

        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }

        $conn->close();
    }

?>