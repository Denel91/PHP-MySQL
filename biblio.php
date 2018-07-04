<?php
if(!isset($_GET['isbn'])) {
    // Se la richiesta non contiene una variabile $isbn allora mostra il form.
    include __DIR__ . './../includes/biblio_form.php';

} else {
    $isbn = $_GET['isbn'];
    header('Content-type: application/json');

    if ($isbn != '') {
        try {
            // Connessione all'host locale e al Database biblioteca
            $pdo = new PDO('mysql:host=localhost; dbname=biblioteca; charset=utf8', 'root', 'segreta');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($pdo !== FALSE) {
                $sql = "SELECT `titolo`, `data_ins`,`num_pagine`,`editore` FROM `libri` 
                        WHERE `isbn` = '" . addslashes($isbn) . "'";
                $result = $pdo->query($sql);
                $res = $result->fetchAll();

                // Se c'è almeno una riga nella tabella libri...
                if(count($res)) {
                    foreach ($res as $record) {

                        $json = '{"isbn" : "'.$isbn.'",
                                  "titolo" : "' . $record['titolo'] . '",
                                  "data_ins" : "' . $record['data_ins'] . '",
                                  "num_pagine" : "' . $record['num_pagine'] . '",
                                  "editore" : "' . $record['editore'] . '",
                                  "autori" : [';

                        $authors = "SELECT a.`id`, a.`nome`, a.`cognome` FROM `autori` AS a, 
                                    `libri_autori` AS al
                                    WHERE `FK_libro` = '" . addslashes($isbn) . "' AND `FK_autore` = a.`id` 
                                    ORDER BY a.`cognome`;";
                        $stmt = $pdo->query($authors);

                        while ($res_authors = $stmt->fetch()) {
                            $json .= '{"nome" : "' . $res_authors['nome'] . '",
                                       "cognome" : "' . $res_authors['cognome'] . '"},';
                        }

                        $json = substr($json, 0, strlen($json) - 1) . '] }';
                        echo $json;
                     }

                } else {

                    $json = '{"isbn" : "Errore",
                             "titolo" : "-",
                             "data_ins" : "_",
                             "num_pagine" : "_",
                             "editore" : "_"
                             "autori" : [] }';
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