<?php
if(!isset($_SESSION))  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Gestione Magazzino INFN sezione di BARI</title>
    <link rel="icon" type="image/x-icon" href="immagini/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/magazzino.css?1">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">

    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  </head>

  
  <body class="magazzino">
    <script src="libs/libFrontEnd.js"></script>
    <?php
      // THIS DEFINE IF THE AUTOCOMPLETATIO IS ON or OFF LINE
      // True := uses the Array
      // False := execute multiple query
      define("AC_OFFLINE", true);

      include_once 'class/configurazione.php';
      include_once 'class/soggetto.php';
      include_once 'libs/libMagazzino.php';
      include_once 'libs/libClassHtml.php';
 
      // set della sessione , lettura configurazione, cronjob
      inizializzaAmbiente();
      // genera il OTPAP
      $_SESSION['OTPAP'] = __generaOTPdiPagina($_SESSION["Server"], session_id());
      $TitoloHeader = "GESTIONE DEL MAGAZZINO SEZIONE DI BARI DELL'INFN v.2.1";
      require("pages/intestazione.php"); 
    ?>
    <div id="corpo" class="mag-corpo">
      <?php 
        require("pages/menu.php"); 
      ?>
      <div id="areadilavoro" class="mag-areadilavoro">
        <?php         
          // Se un utente e' loggato allora Il menu puo' essere abilitato
          $flMenu = (isset($_SESSION['LoggedUser']) && $_SESSION['LoggedUser'] != "") ? true : false;
          // Se la pagina non ha un POST con l'url da caricare
          // oppure l'URL e' login.php => chiama la pagina di login
          if(!isset($_POST['PageUrl']) || $_POST['PageUrl'] === 'login.php' ) {
            require("pages/login.php");
            $flMenu = $flMenu && true; 
          } else {
            require('pages/'.$_POST['PageUrl']); // Deve caricare una pagina !
            $flMenu = false;
          }
          $_SESSION['StatoDelMenu'] = $flMenu; // setta lo stato del menu          
          if($_SESSION['StatoDelMenu']) {
            echo '<script>enableAllButtons();</script>';
          } else {
            echo '<script>disableAllButtons();</script>';
          }
        ?>
      </div>
    </div>
    <?php 
      require("pages/piedipagina.html");
    ?>
  </body>
</html>
