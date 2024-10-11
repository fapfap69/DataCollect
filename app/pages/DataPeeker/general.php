<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>General Input Skeda</title>
  <link rel="stylesheet" type="text/css" href="jquery.ui.combify.css">
  <script type="text/javascript" src="jquery.ui.combify.js"></script>

<style>
.select-editable {
     position:relative;
     background-color:white;
     border:solid grey 1px;
     width:120px;
     height:18px;
 }
 .select-editable select {
     position:absolute;
     top:0px;
     left:0px;
     font-size:14px;
     border:none;
     width:120px;
     margin:0;
 }
 .select-editable input {
     position:absolute;
     top:0px;
     left:0px;
     width:100px;
     padding:1px;
     font-size:12px;
     border:none;
 }
 .select-editable select:focus, .select-editable input:focus {
     outline:none;
 }
</style>

</head>
<body>
 <?php
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
    $kv1 = array( 'KEY1' => 'VAL1', 'KEY2' => 'VAL2', 'KEY3' => 'VAL3');
    $keTy = array('KEY1', 'KEY2', 'KEY3', 'KEY4', 'KEY5', 'KEY6');
    $keylist = array();
    foreach($keTy as $k) {
        if( !array_key_exists($k , $kv1) ) { // it not exists 
            var_dump("Bingo");
            array_push($keylist, $k);
        }
    }
    $numOfFields = 10;

    ?>

  <h1>A general Input Mask</h1>

    <table>
        <tr><th>Key</th><th>Value</th></tr>
        <tr><td>A KEY NUMBER</td><TD><input type="TEXT" value ="P"></td></tr>
<?php
    foreach($kv1 as $k => $v) {
        echo '<tr><td><label style="width: 200px;">'.$k.'</label></td><TD><input type="TEXT" value ="'.$v.'"></td></tr>';
    }
    for ($i = sizeof($kv1); $i<$numOfFields; $i++) {
        $kname = "chiave_".$i;
        $vname = "valore_".$i;
        echo '<tr><td>';
        htmlEditCombo($kname, $keylist);
        echo '</td>';
        echo '<td><input name="'.$vname.'" type="TEXT" value =""></td></tr>';
    }
    
?>
    </table>








</body>
</html>

