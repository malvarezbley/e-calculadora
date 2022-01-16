<?php 
 /*
  Programa          : Calculadora de Indicadores Economicos
  Autor             : Mauricio Álvarez Bley
  Fecha             : 10-08-2018
  Ult.Modificación  : 16-01-2022
  Versión           : 1.7.1
  
  Parametros: Si se utiliza el parametro "act=si" mediante metodo GET, se fuerza a la actualización de los datos
  Ejemplo: www.mabley.cl/calculadora/e-calculadora.php?act=si
  
  */
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Calculadora de Indicadores Económicos</title>
        <!-- <meta name="viewport" content="width=device-width, inicial-scale=1.0"> -->
        
        <style>
            .ocultarPreload{overflow:hidden}.preload{height:100vh;display:flex;justify-content:center;align-items:center;color:#fff}.lds-spinner{color:official;display:inline-block;position:relative;width:80px;height:80px}.lds-spinner div{transform-origin:40px 40px;animation:lds-spinner 1.2s linear infinite}.lds-spinner div:after{content:" ";display:block;position:absolute;top:3px;left:37px;width:6px;height:18px;border-radius:20%;background:#fff}.lds-spinner div:nth-child(1){transform:rotate(0deg);animation-delay:-1.1s}.lds-spinner div:nth-child(2){transform:rotate(30deg);animation-delay:-1s}.lds-spinner div:nth-child(3){transform:rotate(60deg);animation-delay:-.9s}.lds-spinner div:nth-child(4){transform:rotate(90deg);animation-delay:-.8s}.lds-spinner div:nth-child(5){transform:rotate(120deg);animation-delay:-.7s}.lds-spinner div:nth-child(6){transform:rotate(150deg);animation-delay:-.6s}.lds-spinner div:nth-child(7){transform:rotate(180deg);animation-delay:-.5s}.lds-spinner div:nth-child(8){transform:rotate(210deg);animation-delay:-.4s}.lds-spinner div:nth-child(9){transform:rotate(240deg);animation-delay:-.3s}.lds-spinner div:nth-child(10){transform:rotate(270deg);animation-delay:-.2s}.lds-spinner div:nth-child(11){transform:rotate(300deg);animation-delay:-.1s}.lds-spinner div:nth-child(12){transform:rotate(330deg);animation-delay:0}@keyframes lds-spinner{0%{opacity:1}100%{opacity:0}}
        </style>
    </head>

    <body > 
        <div class="preload" id="onloadCarga"> 
            <div class="lds-spinner">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
            <p>Actualizando tasas...</p>
        </div>  

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="css/e-calculadora.css"> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> 
        <script src="js/e-calculadora.js"></script>

<?php

    error_reporting(E_ALL);
    ini_set('display_errors', '0');

    include "funciones.php";  
    $versionCalculadora="1.7";
    verificaArchivoIndicadoresLocal();
    $fechaIndicadores=date("d-m-Y");
    $fechaIndicadoresCompare=date("Y-m-d");
    $forzarActualizacion="no";
    if(isset($_GET['act']))
        $forzarActualizacion=$_GET['act'];

    if (existenIndicadores()!=1 || $forzarActualizacion=="si"){
        //Consulta los indicadores desde los servicios remotos
        $xml=traeIndicadores_SBIF();
        //$xml=traeIndicadores_BCentral();
        //$xml=traeIndicadores_Indicadores();

        $valorDolar=$xml[0][1];
        $valorEuro=$xml[1][1];      
        $valorUF=$xml[2][1];
        $valorUTM=$xml[3][1];   

        $fechaDolar=$xml[0][2];
        $fechaEuro=$xml[1][2];
        $fechaUF=$xml[2][2];
        $fechaUTM=$xml[3][2];

        $valorDolarGuardar=$valorDolar;
        $valorEuroGuardar=$valorEuro;      
        $valorUFGuardar=$valorUF;
        $valorUTMGuardar=$valorUTM;   
        
        //echo $fechaIndicadoresCompare . "=" . $fechaDolar . "<br>";
        
        
        if($fechaIndicadoresCompare!=$fechaDolar)   {
            $dolarEsDiaAnterior=true;
            $valorDolarGuardar="0";
        }
        if($fechaIndicadoresCompare!=$fechaEuro){
            $euroEsDiaAnterior=true;
            $valorEuroGuardar="0";
        }
        
        
        //guardaIndicadores($fechaIndicadores, $valorDolar, $valorEuro, $valorUF, $valorUTM);
        guardaIndicadores($fechaIndicadores, $valorDolarGuardar, $valorEuroGuardar, $valorUFGuardar, $valorUTMGuardar);
    }
    else{
        //Rescata los indicadores almacenados localmente
        $ind=buscaIndicadoresLocales();

        $valorDolar=$ind[0][1];
        $valorEuro=$ind[1][1];      
        $valorUF=$ind[2][1];
        $valorUTM=$ind[3][1];   

        $fechaDolar=$ind[0][2];
        $fechaEuro=$ind[1][2];
        $fechaUF=$ind[2][2];
        $fechaUTM=$ind[3][2];

    }


    $valorDolarFormateado=cambiaFormato($valorDolar);
    $valorUTMFormateado=cambiaFormato($valorUTM);
    $valorUFFormateado=cambiaFormato($valorUF);
    $valorEuroFormateado=cambiaFormato($valorEuro);
    $fechaVisita =date("d-m-y");   

    //0123456789
    //yyyy-mm-dd
    //dd-mm-yy

    //echo "Fecha Dolar : " . $fechaDolar . "<br>";
    //echo "Fecha Euro  : " . $fechaEuro . "<br>";



    $labelIndicadores="Día " . $fechaVisita . "." ;
    $labelIndicadores=$labelIndicadores . " UF:$" . $valorUF;
    $labelIndicadores=$labelIndicadores . "| UTM:$" .  $valorUTM;
    $labelIndicadores=$labelIndicadores . "| Dólar:$" . $valorDolar;
    if($dolarEsDiaAnterior)
        $labelIndicadores=$labelIndicadores . "(" . substr($fechaDolar,0,2) . "-". substr($fechaDolar,3,2) . ")";
    $labelIndicadores=$labelIndicadores . "| Euro:$" .  $valorEuro;
    if($euroEsDiaAnterior)
        $labelIndicadores=$labelIndicadores . "(" . substr($fechaEuro,0,2) . "-" . substr($fechaEuro,3,2) . ")";

    registraVisita();

?>

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
                             <a href="#" class="boton-sel" id="idUF"    onclick="selMoneda('UF');" >UF</a>
                             <a href="#" class="boton-nosel" id="idDolar" onclick="selMoneda('Dolar');">Dólar</a>
                             <a href="#" class="boton-nosel" id="idEuro"  onclick="selMoneda('Euro');">Euro</a>
                             <a href="#" class="boton-nosel" id="idUTM"   onclick="selMoneda('UTM');" >UTM</a>
                            </nav>    
                        </div>
                    </div>
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
                       <div class="hr"></div>
                       <label id="idTituloForm" class="tituloForm">UF a Pesos</label >-->
                       <label id="idLabelValorMoneda">Valor UF: $ <?php echo $valorUF; ?></label>
                        <button type="button" class="btn-intercambio" onclick="invertirValores()" >
                               <img src="imagenes/intercambio4.png" width="25">
                        </button>
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
                                
                                <!-- <input type="checkbox" checked> Redondear resultado -->
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
                      Versión <?php echo $versionCalculadora . " - "; ?> Mabley.cl (Fuente:CMF)
                    </div>
                </div>
          </footer>
          <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
          <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
          <!-- <script src="js/ocultar.js"></script>  -->

         <script>
            window.onload = function(){
                $cont=0;
                for ($cont=1;$cont<=900000000;$cont++)
                {}
            //alert("Terminó de cargar..." + $cont);
            $(".preload").css({visibility:"hidden", opacity:"0"})
            var miDiv = document.getElementById('onloadCarga');
            miDiv.parentNode.removeChild(miDiv);
            }        
        </script> 

    </body>
</html>
