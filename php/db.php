<?php

    define("HOST", "localhost");
    define("USERNAME", "root");
    define("PASSWORD", "");
    define("DBNAME", "trenfe");


    function conectarBBDD() {
        $conn = new mysqli(HOST, USERNAME, PASSWORD, DBNAME);
        if ($conn->connect_error) {
            return false;
        } else {
            return $conn;
        }
    }


    function modificarBBDD($sql) {
        $conn = conectarBBDD();
        if ( !$conn ){
            $conn->close();
            return false;
        }

        if ($conn->query($sql) === TRUE) {
            $conn->close();
            return true;
        } else {
            $conn->close();
            return false;
        }

        $conn->close();
    }


    function consultarBBDD($sql) {
        $conn = conectarBBDD();
        if ( !$conn ){
            $conn->close();
            return false;
        }

        if( ($result = $conn->query($sql)) !== false ){
            $conn->close();
            return $result;
        } else {
            $conn->close();
            return false;
        }

        $conn->close();
    }

?>