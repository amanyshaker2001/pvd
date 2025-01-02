 <!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>

<body>
<p align = "center">

<?php
echo "<table border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='700'>";
include ("connection_info.php");   
$var = mysqli_connect("$server","$name","$password","$database") or die ("connect error");
$sql = "select id_vystup, pred_c,nazovv from vystupy;";
$result = mysqli_query($var, $sql) or exit ("chybny dotaz");
//načítanie hodnôt do pola
while($row = mysqli_fetch_assoc($result))
		{ 
			$id = $row['id_vystup'];
      $nazov = $row['nazovv'];
      $cena = $row['pred_c'];
  
//výpis hodnôt
echo "<tr>
    <td width='200'bgcolor='#ffffff' height='22'><b> ".$id."</b></td>
    <td width='300'bgcolor='#ffffff' height='22'>Nazov<b> ".$nazov."</b></td> 
    <td width='100'bgcolor='#ffffff' height='22'>Cena: <b> ".$cena."</b></td>
    <td width='100'bgcolor='#ffffff' height='22'></td>
	  <td width='100'bgcolor='#ffffff' height='22'></td>
     </tr>
     <tr>


  </tr>   
  <tr>
   <td width='200'bgcolor='#000000' height='1'></td>
    <td width='300'bgcolor='#000000' height='1'></td> 
    <td width='100'bgcolor='#000000' height='1'></td>
    <td width='100'bgcolor='#000000' height='1'></td>
    </tr>";
  }
  echo "</table>";
?>
<a href="index.php" class="btn btn-primary margin-top-5">Naspäť</a>
 </body>
</html>
