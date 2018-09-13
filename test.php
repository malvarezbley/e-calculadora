<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">	
		<title>Calculadora de Indicadores Económicos</title>
		<link rel="stylesheet" href="css/wwwwestilos_mab.css">
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


echo "Dolar:" . $valorDolar . "<br>";
echo "Euro :" . $valorEuro . "<br>";
echo "UF   :" . $valorUF . "<br>";
echo "UTM  :" . $valorUTM . "<br>";
//print_r($xml);
exit;


$valorUTM=$xml->moneda->utm;   
$valorUF=$xml->indicador->uf;
$valorEuro=$xml->moneda->euro;   

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


?>






