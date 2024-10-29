<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>General Input Skeda</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.3/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.3/jquery-ui.min.js"></script>

<!--  <link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.14.0/jquery.ui.combify.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.14.0/jquery.ui.combify.js"></script>
-->
  <link rel="stylesheet" type="text/css" href="../../css/base.css">
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
    $rows   = $DBman->executeQuery('work.mask', $query); 
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
    function writeRecord($DBman, $document) {
       $bulk = new MongoDB\Driver\BulkWrite;
       $record = array();
       $record['action'] = 'Insert';
       $record['date'] = date("D M d, Y G:i");
       $record['state'] = 'Active';
       $record['corpo'] = $document;
       $arr = array();
       array_push($arr, $record);
       $actions['actions'] = $arr;
       $_id1 = $bulk->insert($actions);
       $result = $DBman->executeBulkWrite('work.machines', $bulk);
       return ($result);
    }

   function searchForIdxValue($idx) {
      foreach($_POST as $k => $v) {
         $pos = strpos($k,'valore_');
         if($pos !== false) {
           $idx_a = intval(substr($k, 7));
           if($idx_a === $idx) return($v);
         }
      }
      return(false);
   }
   function searchForLabelValue($campi, $lab) {
      foreach($campi as $campo) {
         if($campo['label'] == $lab) return($campo);
      }
      return(false);
   }
?>
<?php
    // qui costruiamo la pagina
    if(isset($_POST) && count($_POST) > 0) {
	var_dump($_POST);
        echo "<BR>====";
        var_dump($mk);
        echo "<BR>====";
        $record = array();
        $newkeylist = array();

	foreach($_POST as $kItem => $vItem) {
	   if(in_array($kItem, array_column($mk['campi'], 'nome') ) === true) {
	      $record[$kItem] = $vItem;
           } else {
              $pos = strpos($kItem, 'chiave_');
              if($pos !== false && $vItem != "") { // an extra key is find
                $idx = intval(substr($kItem, 7));
                $field = searchForLabelValue($mk['campi'], $vItem);
                if($field !== false) { // the key is predefined
                   $record[$field['nome']] = searchForIdxValue($idx);
                } else { // is a new key
                   $record[$vItem] = searchForIdxValue($idx);
                   array_push($newkeylist, $vItem);
                }
              } 
           }
        }
        echo "<BR>====";
        writeRecord($DBman, $record);
        var_dump($record);
	echo "<BR>====";
	var_dump($newkeylist);
	
	unset($_POST);
    } else {
    echo "<h1>Insert Input Mask</h1>";
    echo "<form action='/app/pages/MongInDat/mask_insert.php' method='post' name='insert'>";
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
   
    echo "<input type='submit' value='Inserisci' name='Puls_1' >";
    echo "</form>";
    }
?>

</body>
</html>
