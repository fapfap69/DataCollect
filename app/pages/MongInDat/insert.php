<?php
// Assicurati di avere l'estensione MongoDB abilitata nel php.ini
// Carica l'estensione MongoDB
if (!extension_loaded("mongodb")) {
    die("L'estensione MongoDB non è caricata.");
}

// Connetti a MongoDB
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Controlla se i dati sono stati inviati tramite POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['name'];
    $email = $_POST['email'];

    // Crea il documento da inserire
    $bulk = new MongoDB\Driver\BulkWrite;
    $doc = ['_id' => new MongoDB\BSON\ObjectID, 'nome' => $nome, 'email' => $email];
    $bulk->insert($doc);

    // Esegui l'inserimento nella collection
    try {
        $manager->executeBulkWrite('test1.users', $bulk);
        echo "Dati inseriti con successo!";
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "Errore durante l'inserimento: ", $e->getMessage();
    }
}
?>

