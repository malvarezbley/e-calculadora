<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">	
		<title>Calculadora de Indicadores Económicos....!!</title>
		<link rel="stylesheet" href="css/estilos_mab.css">
	</head>


<?php

//Si es la primera vez que entra setea moneda por defecto y llama al Webservice
if ($_POST['hidMoneda']== '')  {
	//setea la moneda
	$monedaDefault="UF";

	//Busca los valores del día en WebService
	libxml_use_internal_errors(true);
	$xmlSource = "http://indicadoresdeldia.cl/webservice/indicadores.xml";
	  
	 if (file_exists($xmlSource)) {
	    $xml = simplexml_load_file($xmlSource);
		$valorDolar= $xml->moneda->dolar;
		$valorUF=$xml->indicador->uf;
		$valorEuro=$xml->moneda->euro;   
	  }
	 else 
	 {
	    echo "<h3>No existe XML Buscado</h3>";

		$valorDolar= '644,32';
		$valorUF='27205,11';
		$valorEuro='747,47';       
		$valorUTM='45123,32';
	  }
	  $montoCalculado=0;
	  $valorConversion=0;
	  $tasaConversion=0;
}
else //De lo contrario carga los valores del formulario
{
	$monedaDefault=$_POST['moneda'];
	if($_POST['hidValorDolar'])
		$valorDolar=$_POST['hidValorDolar'];
	if($_POST['hidValorEuro'])
		$valorEuro=$_POST['hidValorEuro'];
	if($_POST['hidValorUF'])
		$valorUF=$_POST['hidValorUF'];
	if($_POST['hidValorUTM'])
		$valorUTM=$_POST['hidValorUTM'];

	$valorAConvertir=$_POST['numValor'];
	$tasaConversion=$_POST['hidTasaConversion'];
	$valorMonedaSeleccionada=$_POST['hidValorMonedaSeleccionada'];

}

	if ($_POST['numValor']!=''){
	//convertir a numerico
		$valorConversion=$_POST['numValor'];+0;
		$tasa=$valorMonedaSeleccionada+0;

		$montoCalculado=$valorConversion*$tasa;
		//$montoCalculado=12233;
	}

	echo "tasa: " . $_POST["hidTasaConversion"];
?>




<body>

  <!-- Etiquetas HTML5 -->
  <header>
  	<h1>Calculadora de Indicadores Economicos...</h1 >
  	<p>Fecha: <?php echo date("d-m-Y"); ?> </p>  	

   <nav>
   		<a href="#"  class="linkMenu">Inicio</a>
   		<a href="#"  class="linkMenu">Unidades Medida</a>
   		<a href="#"  class="linkMenu">Configuración</a>

   </nav>

  </header>

  <main>
  	<section>
  		<p>Indicadores del día:</p>	
		<p>
			<label> UF: <b><?php echo $valorUF; ?></b> | </label>
			<label> UTM: <b><?php echo $valorUTM; ?></b> | </label>
	 		<label> Dólar:<b><?php echo $valorDolar;?></b> | </label>
			<label> Euro:<b><?php echo $valorEuro;?></b> </label>

		</p>

		<form action="./calculadora.php" method="POST" 
		       oninput="resultado.value=parseInt(hidValorMonedaSeleccionada.value) * parseInt(numValor.value)"  >
			<p>Seleccione moneda:</p>
			<p>
				<input type="hidden" name="hidValorDolar" value="<?php echo $valorDolar; ?>">
				<input type="hidden" name="hidValorEuro" value="<?php echo $valorEuro; ?>"> 
				<input type="hidden" name="hidValorUF" value="<?php echo $valorUF; ?>">
				<input type="hidden" name="hidValorUTM" value="<?php echo $valorUTM; ?>">
				<input type="hidden" name="hidMoneda" value="<?php echo $monedaDefault; ?>">
				<input type="hidden" name="hidTasaConversion" value="<?php echo $monedaDefault; ?>">



				<input type="radio" name="moneda" <?php if($monedaDefault=="UF") { echo "checked"; } ?> value="UF"> UF
				<input type="radio" name="moneda" <?php if($monedaDefault=="DOLAR") { echo "checked"; } ?> value="DOLAR"> Dolar
				<input type="radio" name="moneda" <?php if($monedaDefault=="EURO") { echo "checked"; } ?> value="EURO"> Euro
				<input type="radio" name="moneda" <?php if($monedaDefault=="UTM") { echo "checked"; } ?> value="UTM"> UTM
			</p>
			<p>
				Convertir de <?php echo $monedaDefault; ?> a Pesos
			</p>
			<p>
				<?php

				switch ($monedaDefault) {
					case 'DOLAR':
						$valorMonedaSeleccionada=$valorDolar;
						break;
					case 'EURO':
						$valorMonedaSeleccionada=$valorEuro;
						break;
					case 'UF':
						$valorMonedaSeleccionada=$valorUF;
						break;
					case 'UTM':
						$valorMonedaSeleccionada=$valorUTM;
						break;
					
					default:
						# code...
						break;
				}

				?>
				Valor Actual: $ <?php echo $valorMonedaSeleccionada; ?> 
			</p>
			<div class="formulario">
				<section class="labelsForm">
					<p>
						<label>Valor a Convertir</label>
					</p>
					<p>
					 <label>Total Pesos</label>
					</p>
				</section>
				<section class="datosForm">
					<p>
					<input type="hidden" name="hidValorMonedaSeleccionada" 
					       value="<?php echo $valorMonedaSeleccionada;?>">	
					<input type="number" step="any" name="numValor" class="_cajaTexto" 
					       placeholder="Ingrese un valor" 
					       value="<?php echo $valorConversion; ?>"/> 
					<input type="submit" name="btnCalcular" value="Calcular"/>
					</p>
					<p>
					<output  name="resultado" class="_cajaTexto" for="hidValorMonedaSeleccionada numValor"> </output>	
					<label><?php echo $montoCalculado; ?> <label/> 
					</p>
				</section>
			</div>
			<section class="botoneraForm">
					<input type="submit" name="btnCalcular" value="Calcular"/>
			</section>
		</form>

  	</section>
  </main>
  <!-- Fin HTML5 -->

  <footer>
  	<p>
  		Todos los derechos reservados <strong>Mabley.cl</strong>
  	</p>
  </footer>

</body>
</html>




