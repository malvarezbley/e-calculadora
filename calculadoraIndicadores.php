<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">	
		<title>Calculadora de Indicadores Económicos</title>
		<link rel="stylesheet" href="css/estilos_mab.css">
		<!--  <script src="js/calculadoraIndicadores.js"></script>  -->
		<script type="text/javascript">
			function seleccionaMoneda(){

				var radioButTrat = document.getElementsByName("moneda");
				var valorMonedaSeleccionada = document.getElementsByName("hidValorMonedaSeleccionada");

				for (var i=0; i<radioButTrat.length; i++) {
					if (radioButTrat[i].checked == true) {
					    monedaSel=radioButTrat[i].value;
					}
				} 

				switch(monedaSel) {
				    case "UF":
				        var valorMoneda=document.getElementById("valorUF").value;
				        document.getElementById("glosaConvetir").innerHTML="UF a Pesos";
				        break;
				    case "UTM":
				        var valorMoneda=document.getElementById("valorUTM").value;
				        document.getElementById("glosaConvetir").innerHTML="UTM a Pesos";
				        break;
				    case "DOLAR":
				    	var valorMoneda=document.getElementById("valorDolar").value;
				    	document.getElementById("glosaConvetir").innerHTML="Dolar a Pesos";
				        break;
				    case "EURO":
				        var valorMoneda=document.getElementById("valorEuro").value;
				        document.getElementById("glosaConvetir").innerHTML="Euros a Pesos";
				        break;

				    default:
				        valorMoneda="0";
				}
				document.getElementById("valorMonedaSel").value=valorMoneda;
				document.getElementById("valorActual").innerHTML="Valor Actual: $ " + valorMoneda;
			
			}

			function formateaRes(valorAFormatear){
				valorAFormatear="99.999,23";
			}	
		</script>
	</head>
<?php

$modo=$_GET["modo"];

//setea la moneda
$monedaDefault="UF";

//Busca los valores del día en WebService
libxml_use_internal_errors(true);
//$xmlSource = "http://indicadoresdeldia.cl/webservice/indicadores.xml";

$xmlSource1 = "http://api.sbif.cl/api-sbifv3/recursos_api/dolar?apikey=3c3637632fa57cf2f1f093dd2c2a5213f86f0a23&formato=xml";
$xmlSource2 = "http://api.sbif.cl/api-sbifv3/recursos_api/euro?apikey=3c3637632fa57cf2f1f093dd2c2a5213f86f0a23&formato=xml";
$xmlSource3 = "http://api.sbif.cl/api-sbifv3/recursos_api/uf?apikey=3c3637632fa57cf2f1f093dd2c2a5213f86f0a23&formato=xml";
$xmlSource4 = "http://api.sbif.cl/api-sbifv3/recursos_api/utm?apikey=3c3637632fa57cf2f1f093dd2c2a5213f86f0a23&formato=xml";

$xml1 = simplexml_load_file($xmlSource1);
$xml2 = simplexml_load_file($xmlSource2);
$xml3 = simplexml_load_file($xmlSource3);
$xml4 = simplexml_load_file($xmlSource4);


//$valorDolar= $xml->moneda->dolar;
$valorDolar= $xml1->Dolares->Dolar->Valor;
$valorEuro= $xml2->Euros->Euro->Valor;
$valorUF= $xml3->UFs->UF->Valor;
$valorUTM= $xml4->UTMs->UTM->Valor;


/*$xml = simplexml_load_file($xmlSource);
if ($xml === false) {
    echo "Error cargando XML\n";
    foreach(libxml_get_errors() as $error) {
        echo "\t", $error->message;
    }
}

$valorDolar= $xml->moneda->dolar;
$valorUTM=$xml->moneda->utm;   
$valorUF=$xml->indicador->uf;
$valorEuro=$xml->moneda->euro;   
*/

$valorDolarNumerico=str_replace(".", "", $valorDolar);
$valorEuroNumerico=str_replace(".", "", $valorEuro);
$valorUFNumerico=str_replace(".", "", $valorUF);
$valorUTMNumerico=str_replace(".", "", $valorUTM);


//busca la IP de Origen 
if (!empty($_SERVER['HTTP_CLIENT_IP']))
	$ipOrigen=$_SERVER['HTTP_CLIENT_IP'];
elseif  (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	$ipOrigen=$_SERVER['HTTP_X_FORWARDED_FOR'];
else
	$ipOrigen=$_SERVER['REMOTE_ADDR'];

//buscamos la fecha hora 
$fechaHora=date("d-m-Y i:s");
//detectamos pais de origen
$ch = curl_init("http://api.hostip.info/country.php?ip=$ipOrigen");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$paisOrigen=curl_exec($ch);
 $registro=$fechaHora . "|" . $ipOrigen . "|" . $paisOrigen ."\n";


//-----------------------------------
//registra el uso de la calculadora
//-----------------------------------
 $numLineas=0;
$archivoLog="./calculadoraIndicadores.log";
if(!file_exists($archivoLog)){
	//Crea archivo
	$arch=fopen($archivoLog, 'a');
	$titulo="FECHA|IP ORIGEN|PAIS ORIGEN\n";
	fwrite($arch, $titulo);
}
else{
    $arch=fopen($archivoLog, 'r+');
    while (!feof($arch)){
    	if($linea=fgets($arch))
    		$numLineas++;
    }
}
fwrite($arch, $registro);
$visitas=$numLineas;
fclose($arch);


?>

<body>
  <header>
  	<?php 
 	if($modo=="") { ?>	
  	<h1>Calculadora de Indicadores Financieros...</h1 >
  	
   <nav>
   </nav>
  <?php } ?>
  </header>

  <main>
  	<section>
  		<p>Fecha: <?php echo date("d-m-Y"); ?> </p>  	
  		<p>Indicadores del día:
  			<label> UF: <b><?php echo $valorUF; ?></b> | </label>
			<label> UTM: <b><?php echo $valorUTM; ?></b> | </label>
	 		<label> Dólar:<b><?php echo $valorDolar;?></b> | </label>
			<label> Euro:<b><?php echo $valorEuro;?></b> </label>
		</p>

		<form action="./calculadora.php" method="POST" 
		       oninput="resultado.value=parseFloat(hidValorMonedaSeleccionada.value) * parseFloat(numValor.value)"  >
			<p>Seleccione moneda:</p>
			<p>
				<input type="hidden" id="valorDolar" name="hidValorDolar" value="<?php echo $valorDolarNumerico; ?>">
				<input type="hidden" id="valorEuro" name="hidValorEuro" value="<?php echo $valorEuroNumerico; ?>"> 
				<input type="hidden" id="valorUF" name="hidValorUF" value="<?php echo $valorUFNumerico; ?>">
				<input type="hidden" id="valorUTM" name="hidValorUTM" value="<?php echo $valorUTMNumerico; ?>">
				<input type="hidden" id="valorMonedaSel" name="hidValorMonedaSeleccionada" value="<?php echo $valorUFNumerico;?>">	

				<input type="radio" id="mon" name="moneda" value="UF"  onClick="seleccionaMoneda()" checked="true" > UF
				<input type="radio" id="mon" name="moneda" value="DOLAR"  onClick="seleccionaMoneda()" > Dolar
				<input type="radio" id="mon" name="moneda" value="EURO" onClick="seleccionaMoneda()" > Euro
				<input type="radio" id="mon" name="moneda" value="UTM" onClick="seleccionaMoneda()"> UTM
			</p>
			<p>
				<label id="valorActual"> Valor Actual: $ <?php echo $valorUF; ?> </label>
			</p>
		     <p>Convertir de: </p>
			 <p><label id="glosaConvetir">UF a Pesos</label>
				<input type="number" step="any" name="numValor" class="cajaTexto" placeholder="Ingrese un valor" autofocus="true" min="0" max="9999999999" maxlength="10" /> 
				<label id="glosaConvertido">Total Pesos</label>
				<output name="resultado" for="hidValorMonedaSeleccionada numValor" > 0</output>	
			 </p>
			   <p>
			 	 <?php echo "<br><br>Visitante Número:" . $visitas . "   " ; ?>
			  </p>
		</form>

  	</section>
  </main>
  
  	<?php 
 	if($modo=="") { ?>	
	  <footer>
	  	<p>
	  		Todos los derechos reservados <strong>Mabley.cl</strong>
	  	</p>
	  </footer>
	<?php } ?>  
</body>
<!-- Fin HTML5 -->
</html>




