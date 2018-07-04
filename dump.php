<?php
if(!isset($_GET['isbn'])) {
    // Se la richiesta non contiene una variabile $isbn allora mostra il form.
    include __DIR__ . './../includes/isbn_form.php';
} else {
    $isbn = $_GET['isbn'];

        if ($isbn != '') {
            try {
                // Connessione all'host locale e al Database libreria
                $pdo = new PDO('mysql:host=localhost; dbname=libreria; charset=utf8', 'root', 'segreta');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if ($pdo !== FALSE) {
                    $sql = "SELECT * FROM libreria.`libri` WHERE `isbn` = '" . addslashes($isbn) . "'";
                    $result = $pdo->query($sql);
                    $res = $result->fetchAll();

                    if (count($res) == 0) {
                        echo '{
                                "isbn" : "-",
                                "titolo" : "-",
                                "autore" : "-",
                                "data_pub" : "-",
                                "editore" : "-",
                                "num_pagine" : "-"  
                               }';

                    } else {
                        foreach ($res as $record) {
                            echo '{
                                    "isbn" : "' . $record['isbn'] . '",
                                    "titolo" : "' . $record['titolo'] . '",
                                    "autore" : "' . $record['autore'] . '",
                                    "data_pub" : "' . $record['data_pub'] . '",
                                    "editore" : "' . $record['editore'] . '",
                                    "num_pagine" : "' . $record['num_pagine'] . '"}';
                        }
                    }
                }

                $pdo = null; // Chiudo la Connessione al DataBase.

            } catch (PDOException $e) {
                $output = 'Database error: ' . $e->getMessage() . ' in ' .
                    $e->getFile() . ':' . $e->getLine();

                include __DIR__ . './../includes/Data_Error.php';
            }

        } else {

            echo 'ISBN mancante!';
        }
}