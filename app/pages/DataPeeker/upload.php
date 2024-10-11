<?php
// Verifica che il file sia stato caricato correttamente
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    // Directory di destinazione
    $uploadDir = 'uploads/';
    
    // Crea la directory se non esiste
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Ottieni i dettagli del file
    $fileTmpPath = $_FILES['photo']['tmp_name'];
    $fileName = $_FILES['photo']['name'];
    $fileSize = $_FILES['photo']['size'];
    $fileType = $_FILES['photo']['type'];
    
    // Percorso di destinazione
    $dest_path = $uploadDir . uniqid() . '-' . basename($fileName);
    
    // Sposta il file nella cartella uploads
    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $response = [
            "status" => "success",
            "message" => "Upload completato con successo!",
            "file" => $dest_path
        ];
    } else {
        $response = [
            "status" => "error",
            "message" => "Errore durante il caricamento del file."
        ];
    }
} else {
    $response = [
        "status" => "error",
        "message" => "Nessun file caricato o errore durante il caricamento."
    ];
}

// Restituisce la risposta in formato JSON
header('Content-Type: application/json');
echo json_encode($response);

?>