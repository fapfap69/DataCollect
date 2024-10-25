<?php
require 'vendor/autoload.php'; // Carica Composer autoload

// Connetti a MongoDB
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->test1->users;

// Controlla se i dati sono stati inviati tramite POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ricevi i dati dal form
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Crea il documento da inserire
    $document = [
        'nome' => $name,
        'email' => $email
    ];

    // Inserisci il documento nella collection
    $result = $collection->insertOne($document);

    if ($result->getInsertedCount() == 1) {
        echo "Dati inseriti con successo!";
    } else {
        echo "Errore durante l'inserimento.";
    }
}
?>
