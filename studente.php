<?php
if (!isset($_GET['matricola'])) {
    // Se la richiesta non contiene una variabile $matricola allora mostra il form.
    include __DIR__ . './../includes/matricola_form.php';

} else {
    $matricola = $_GET['matricola'];

    if ($matricola != '') {
        try {
            // Connessione all'host locale e al Database libretto
            $pdo = new PDO('mysql:host=localhost; dbname=libretto; charset=utf8', 'root', 'segreta');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if($pdo !== FALSE) { // Se la connessione è valida...
                $sql = "SELECT * FROM libretto.`studenti` WHERE `matricola`= '" . addslashes($matricola) . "'";
                $result = $pdo->query($sql);
                $res = $result ->fetchAll();

                if (count($res) == 0) {
                    echo '{
                            "matricola" : "-",
                            "nome" : "-",
                            "cognome" : "-",
                            "data_nascita" : "-",
                            "corso" : "-" 
                           }';
                } else {
                    foreach ($res as $record) {
                        echo '{
                                "matricola" : "' . $record['matricola'] . '",
                                "nome" : "' . $record['nome'] . '",
                                "cognome" : "' . $record['cognome'] . '",
                                "data_nascita" : "' . $record['data_nascita'] . '",
                                "corso" : "' . $record['corso'] . '"
                               }';
                    }
                }
            }

            $pdo = null; // Chiudo la Connessione al DataBase.

        } catch (PDOException $e) { //Catturo le eccezioni.
            $output = 'Database error: ' . $e->getMessage() . ' in ' .
                $e->getFile() . ':' . $e->getLine();

            include __DIR__ . './../includes/Data_Error.php';
        }

    } else {

        echo 'Matricola mancante!';
    }
}







