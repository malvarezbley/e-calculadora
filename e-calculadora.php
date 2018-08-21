<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Calculadora de Indicadores Económicos</title>
        <meta name="viewport" content="width=device-width, inicial-scale=1.0">
        <link rel="stylesheet" href="css/e-calculadora.css">
        <script src="js/calculadoraIndicadores.js"></script>
    </head>
    <body>
        <header>
            <h1>Calculadora de Indicadores Económicos</h1>
            <h4>Indicadores del 15-08-208. UF:27.227,90 | UTM:47.779 | Dólar:658,70| Euro: 751,18</h4>
        </header>
        <nav class="nav-menu">
           <label class="nav-label">Elija la Moneda:</label>
            <a href="#" class="boton-sel" id="idUF"    onclick="selMoneda('UF');">UF</a>
            <a href="#" class="boton-nosel" id="idDolar" onclick="selMoneda('Dolar');">Dolar</a>
            <a href="#" class="boton-nosel" id="idEuro"  onclick="selMoneda('Euro');">Euro</a>
            <a href="#" class="boton-nosel" id="idUTM"   onclick="selMoneda('UTM');" >UTM</a>
        </nav>    
        <section>
            <form class="form-calculadora">
               
               <h2 class="form-titulo" id="idTituloForm">Calculadora: UF a Pesos</h2>
               <label class="form-texto" id="idValorMoneda">Valor UF: 27.227,90</label>
               <input type="hidden" name="hidMoneda" value="UF" id="idMoneda">
               <input type="hidden" name="hidValorMoneda" value="" id="idValorMoneda">     

               <input type="hidden" name="hidValorUF" value="27227.95" id="idValorUF">     
               <input type="hidden" name="hidValorDolar" value="658.70" id="idValorDolar">     
               <input type="hidden" name="hidValorEuro" value="751.18" id="idValorEuro">     
               <input type="hidden" name="hidValorUTM" value="47779" id="idValorUTM">
               <input type="hidden" name="hidValorMonedaSeleccionada" value="27227.95" id="idValorMonedaSel">
               <input type="hidden" name="hidFlagConversion" value="1" id="idFlagConversion">    
               <div class="form-contenedor-input">
                 <label class="form-label" id="idLabelOrigen">Convertir desde UF</label>
                 <input type="number" 
                        class="form-input" 
                        name="valorInput" min="0" max="999999999" step="any"
                        id="idValorInput"
                        autofocus
                        onkeyup="calculaValor()" 
                        placeholder="Ingrese Valor" required>
                 <label class="form-label" id="idLabelDestino">Pesos</label>
                 <input type="text" 
                        class="form-input"
                        id="idValorOutput"
                        step="any"
                        readonly
                        name="valorOutput" >
                  <button type="button" class="btn-intercambio" onclick="invertirValores()" >
                     <img src="imagenes/intercambio4.png" width="35">
                  </button>
               </div>
            </form>            
        </section>
        <footer>
            <p class="footer-derechos">
            Todos los derechos reservados: Mabley.cl 
            </p>
          <p class="footer-visitas">
              V:1212212
          </p>

        </footer>
    </body>
</html>
