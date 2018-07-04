<?php
    if (!isset($_GET['codprodotto'])) {
        // Se la richiesta non contiene una variabile $codprodotto...
        echo 'Non hai inserito nessun codice prodotto!';

    } else {
        $codprodotto = $_GET['codprodotto'];

        $prodotti = [
            1 => 25,
            2 => 30,
            3 => 35,
            4 => 40,
            5 => 45
        ];

        echo $prodotti[$codprodotto];
    }