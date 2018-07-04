<?php
$studenti = file('studenti.txt');
    if(count($studenti) > 0) { //Se ho letto almeno una riga
        try {
            // Connessione all'host locale e al Database libretto
            $pdo = new PDO('mysql:host=localhost; dbname=libretto; charset=utf8', 'root', 'segreta');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            echo "<table>\n";
                foreach ($studenti as $studente) {
                    $campi = explode(':', $studente);
                    echo "<tr>";
                        foreach ($campi as $columns) {
                            echo "<td>$columns</td>";
                        }
                     echo "</tr>";
                }
            echo "</table>";

            if($pdo !== FALSE) { // Se la connessione è valida esegui la query.
                foreach ($studenti as $studente) {
                    $campi = explode(':', $studente);
                    $sql = "SELECT count(`matricola`) FROM libretto.`studenti` 
                            WHERE `matricola`= '" . addslashes($campi[0]) . "'";
                    $result = $pdo->query($sql);
                    $res = $result->fetchColumn();

                    if ($res == 0) {
                        $query = "INSERT INTO libretto.`studenti` (`matricola`, `nome`, `cognome`, `data_nascita`, `corso`) 
                                  VALUES ('" . addslashes($campi[0]) . "', '" . addslashes($campi[1]) . "', 
                                  '" . addslashes($campi[2]) . "', '" . addslashes($campi[3]) . "', 
                                  '" . addslashes($campi[4]) . "')";
                        $pdo->exec($query);

                    } else {
                        $query = "UPDATE libretto.`studenti` SET 
                                    nome = '" . addslashes($campi[1]) . "',
                                    cognome = '" . addslashes($campi[2]) . "',
                                    data_nascita = '" . addslashes($campi[3]) . "',
                                    corso = '" . addslashes($campi[4]) . "'
                                  WHERE `matricola`= '" . addslashes($campi[0]) . "'";
                        $pdo->exec($query);
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

        echo 'Il file non esiste!';
    }