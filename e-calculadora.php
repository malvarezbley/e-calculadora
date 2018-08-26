<?php 
 /*
  Programa          : Calculadora de Indicadores Economicos
  Autor             : Mauricio Álvarez Bley
  Fecha             : 10-08-2018
  Ult.Modificación  : 25-08-2018
  Versión           : 1.2
  */

error_reporting(E_ALL);
ini_set('display_errors', '1');

include "funciones.php";  
$versionCalculadora="1.2";
$xml=traeIndicadores();
$valorDolar= $xml->moneda->dolar;
$valorUTM=$xml->moneda->utm;   
$valorUF=$xml->indicador->uf;
$valorEuro=$xml->moneda->euro;      

$valorDolarFormateado=cambiaFormato($valorDolar);
$valorUTMFormateado=cambiaFormato($valorUTM);
$valorUFFormateado=cambiaFormato($valorUF);
$valorEuroFormateado=cambiaFormato($valorEuro);
$fechaVisita =date("d-m-y");   
$labelIndicadores="Fecha: $fechaVisita UF:$ $valorUF  | UTM:$  $valorUTM  | Dólar:$  $valorDolar  | Euro:$ $valorEuro"; 
registraVisita();

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Calculadora de Indicadores Económicos</title>
        <!-- <meta name="viewport" content="width=device-width, inicial-scale=1.0"> -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="css/e-calculadora.css">  
        <script src="js/calculadoraIndicadores.js"></script>
    </head>
    <body>
        <h1 class="titulo-contenedor">Calculadora de Indicadores Económicos</h1>
          <div class="container contenedor-calculadora">
                <header>
                   <center>
                    <label class="label-indicadores"><?php echo $labelIndicadores; ?></label>
                    </center>
                    <div class="row">
                        <div class="col-">
                            <label class="label-moneda">Moneda:</label>
                        </div>
                        <div class="col"> 
                            <nav>
                             <a href="#" class="boton-sel" id="idUF"    onclick="selMoneda('UF');">UF</a>
                             <a href="#" class="boton-nosel" id="idDolar" onclick="selMoneda('Dolar');">Dolar</a>
                             <a href="#" class="boton-nosel" id="idEuro"  onclick="selMoneda('Euro');">Euro</a>
                             <a href="#" class="boton-nosel" id="idUTM"   onclick="selMoneda('UTM');" >UTM</a>
                            </nav>    
                        </div>
                    </div>
                    <hr>
                </header>
                <section>
                    <form>
                       <input type="hidden" name="hidMoneda" value="UF" id="idMoneda">
                       <input type="hidden" name="hidValorMoneda" value="" id="idValorMoneda">     
                       <input type="hidden" name="hidValorUF" value="<?php echo $valorUFFormateado; ?>" id="idValorUF">     
                       <input type="hidden" name="hidValorDolar" value="<?php echo $valorDolarFormateado; ?>" id="idValorDolar">     
                       <input type="hidden" name="hidValorEuro" value="<?php echo $valorEuroFormateado; ?>" id="idValorEuro">     
                       <input type="hidden" name="hidValorUTM" value="<?php echo $valorUTMFormateado; ?>" id="idValorUTM">
                       <input type="hidden" name="hidValorMonedaSeleccionada" value="<?php echo $valorUFFormateado; ?>" id="idValorMonedaSel">
                       <input type="hidden" name="hidFlagConversion" value="1" id="idFlagConversion">    
                       <label id="idTituloForm">UF a Pesos</label>-->
                       <label id="idLabelValorMoneda">Valor UF: $ <?php echo $valorUF; ?></label>
                        <div class="row">  
                            <div class="col-sm-2">
                             <label id="idLabelOrigen">Desde UF</label>
                            </div>
                            <div class="col-sm-3">
                             <input type="number" 
                                class="form-input" 
                                name="valorInput" min="0" max="999999999" step="any"
                                id="idValorInput"
                                autofocus
                                onkeyup="calculaValor()" 
                                placeholder="Ingrese Valor" required>
                            </div>
                            <div class="col-sm-2">
                             <label class="form-label" id="idLabelDestino">a Pesos</label>
                            </div> 
                            <div class="col-sm-3">
                             <input type="text" 
                                class="form-input"
                                id="idValorOutput"
                                step="any"
                                readonly
                                name="valorOutput" >
                            </div>
                            <div class="col-sm-2">
                             <button type="button" class="btn-intercambio" onclick="invertirValores()" >
                               <img src="imagenes/intercambio4.png" width="35">
                             </button>
                            </div>
                        </div>        
                    </form>            
                </section>
          </div>
          <footer>
                <div class="row " >
                    <div class="col footer-visitas">
                      V#:<?php echo totalVisitasCalculadora(); ?>      
                    </div>
                    <div class="col footer-derechos">
                      Versión <?php echo $versionCalculadora . " - "; ?> Todos los derechos reservados: Mabley.cl
                    </div>
                </div>
          </footer>
          <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
          <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>
