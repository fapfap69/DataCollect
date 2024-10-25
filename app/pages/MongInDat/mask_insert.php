<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>General Input Skeda</title>
  <link rel="stylesheet" type="text/css" href="jquery.ui.combify.css">
  <script type="text/javascript" src="jquery.ui.combify.js"></script>
  <link rel="stylesheet" type="text/css" href="app/css/base.css">
</head>

<body>
<?php
    // questa funzione serve a gestire le combo box ...
    function htmlEditCombo($name, $opts) {
        echo '<div class="select-editable">';
        echo '<select onchange="this.nextElementSibling.value=this.value">';
        echo '<option value=""></option>';
        foreach($opts as $o) { echo '<option value="'.$o.'">'.$o.'</option>';}
        echo '</select>';
        echo '<input type="text" name="'.$name.'" value="" />';
        echo '</div>';
    }
?>

<?php
    // MOngo DB set up
    // Carica l'estensione MongoDB
    if (!extension_loaded("mongodb")) {
        die("L'estensione MongoDB non è caricata.");
    }
    // Connetti a MongoDB
    $DBman = new MongoDB\Driver\Manager("mongodb://localhost:27017");

    // Esegui la query della maschera
    $filter  = ['nome' => 'Insert'];
    $options = [];
    $query = new \MongoDB\Driver\Query($filter, $options);
    $rows   = $mongo->executeQuery('work.mask', $query); 
    foreach ($rows as $jMask) {
        $mk = json_decode(json_encode($jMask),true);
    }

    // crea l'array delle chiavi 
    $keylist = array();
    foreach($mk['campi'] as $campo) {
        if($campo['nonnull'] == false) {
            array_push($keylist, $campo['label']); 
        }
    }
?>


<?php
    // qui costruiamo la pagina
    echo "<h1>Insert Input Mask</h1>";
    echo "<table>";

    $numCampi = 0;

    $v = "";
    foreach($mk['campi'] as $campo) {
        if($campo['nonnull'] == true) { // campo obbligatorio
            echo '<tr><td><label style="width: 200px;">'.$campo['label'].'</label></td>';
            echo '<td><input type="'.$campo['tipo'].'" name="'.$campo['nome'].'" len="'.$campo['lun'].'" value ="'.$v.'"></td></tr>'; 
            $numCampi++;
        } 
    }
    for($i=$numCampi+1; $i<=$mk['numcampi']; $i++) {
        $kname = "chiave_".$i;
        $vname = "valore_".$i;
        echo '<tr><td>';
        htmlEditCombo($kname, $keylist);
        echo '</td>';
        echo '<td><input name="'.$vname.'" type="TEXT" len="255" value ="'.$v.'"></td></tr>';
    }
    echo "</table>";

?>

</body>
</html>