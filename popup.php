<!DOCTYPE html>
<html>
<head>
  <link rel="icon" href="./img/logo_001.png" sizes="16x16">
	<title>webindicator | EXELCO</title>
	<link rel="stylesheet" href="css/styles_tab.css">
</head>
<body>

<?php session_start();
if (!isset($_SESSION['user'])) { //comprobacion si el user esta logeado
    header('Location: ../index.php');
  }
// require './config/conn.php';
require "config/oconn.php";

if (isset($_GET['company']) && isset($_GET['date']) && isset($_GET['section']) && isset($_GET['name'])):
$idcompany = $_GET['company'];
$date = date("Ym", strtotime($_GET['date']));
$section = $_GET['section'];
$name = $_GET['name'];
if ($section == "USA ALIMENTOS") { //codigo de section irrelevante
   $section = "ALIMENTOS EU";
}

//clase conexion oci 
$oci = new ociDB();
$oci->connect();
$sql = "SELECT poli_folio, pold_fecha, polm_totcar, polm_totcre, polc_origen, poly_status, nvl(polc_descrip,' ') polc_descrip
FROM ctb_polizas 
where ctbs_cia = '".$idcompany."' and poli_aammejer = '$date' and (poly_status = 0 or poly_status = 1) ORDER BY pold_fecha";
$array = $oci->getRows($sql, OCI_NUM);
echo "<h5>Date: ".$_GET['date']."</h5><center><h3 id='$idcompany'>$idcompany | $name | $section </h3></center><br>";
 ?>
 <table class="pTable">
      <thead>
        <tr><th>FOLIO</th><th>DATE</th><th>CHARGE</th><th>CREDIT</th><th>ORIGIN</th><th>STATUS</th><th>DESCRIPTION</th></tr>
      </thead>
      <tbody>
        <?php 
          if (!$array) {
            echo "<tr><td colspan='7'>nothing to show...</td></tr>";
          }
          else{
            for ($i=0; $i < count($array) ; $i++) {
            $status = ($array[$i][5] == "1")?"CAPTURED":"ERROR"; 
             echo "<tr>". 
              "<td>". $array[$i][0] ."</td>".
              "<td>". $array[$i][1] ."</td>".
              "<td>". $array[$i][2] ."</td>".
              "<td>". $array[$i][3] ."</td>".
              "<td>". $array[$i][4] ."</td>".
              "<td>". $status ."</td>".
              "<td>". $array[$i][6] ."</td>".
              "</tr>";
            }
          }
         ?>
      </tbody>
  </table>
<?php endif; //end isset?>
</body>


<style type="text/css">
  body{
   background-color: snow; 
  }
  .pTable{
    text-align: center;
    border: 2px solid black;
    border-collapse: collapse;
    margin: 0 auto;
    font-size: 14px;
  }
  .pTable td, th{
    border: 1px solid black;
    padding: 2px 4px; 
    max-width: 100px;
    overflow-x: auto;
    max-width: 170px;
  }
</style>
<script type="text/javascript">
  //document.getElementById("807").innerText = "807 | REAL ESTATE | ALIMENTOS EU";
</script>
</html>