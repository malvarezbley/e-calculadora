<?php
function registraVisita(){
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
    $paisOrigen="";
    
    /*
    -----------------------------------------------------------------------------------------------
    Se comentó debido al tiempo que se demora la funcion en buascar el pais al que pertenece la IP
    -----------------------------------------------------------------------------------------------
    
    $ch = curl_init("http://api.hostip.info/country.php?ip=$ipOrigen");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $paisOrigen=curl_exec($ch);
    */
    
    if($paisOrigen==""){
        $paisOrigen="N/A";
    }
    
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
    fclose($arch);
}


function existenIndicadores(){
    /*
    Valores de retorno:
    0 = No Existen indicadores para el dia consultado
    1 = Existen indicadores para el dia consulado
    2 = Solo existen algunos indicaores
    */
    $anno=date("Y");
    $archivoLocal="./indicadores_" . $anno . ".txt";
    $diaActual=date("d-m-Y");
    $arch=fopen($archivoLocal, 'r+');
    $retorno=0;
    while (!feof($arch)){
        $reg=fgets($arch);
        if(strlen($reg)>0){
            list($fecha, $uf, $dolar, $euro, $utm) = explode('|', $reg);
            if ($diaActual==$fecha){
                if ($uf!="0" && $dolar!="0" && $euro!="0" && $utm!="0")
                    $retorno=1;
                else
                    $retorno=2;

            }
            
        }
    }
    fclose($arch);
    return $retorno;
}
    


function buscaIndicadoresLocales(){
    $anno=date("Y");
    $archivoLocal="./indicadores_" . $anno . ".txt";
    $diaActual=date("d-m-Y");
    $arch=fopen($archivoLocal, 'r+');
    
    $retorno[0][1]="0";
    $retorno[0][2]=$diaActual;
    $retorno[1][1]="0";
    $retorno[1][2]=$diaActual;
    $retorno[2][1]="0";
    $retorno[2][2]=$diaActual;
    $retorno[3][1]="0";
    $retorno[3][2]=$diaActual;
    
    while (!feof($arch)){
        $reg=fgets($arch);
        if(strlen($reg)>0){
            list($fecha, $uf, $dolar, $euro, $utm) = explode("|", $reg);
            if ($diaActual==$fecha){
                $retorno[0][1]=$dolar;
                $retorno[0][2]=$fecha;
                $retorno[1][1]=$euro;
                $retorno[1][2]=$fecha;
                $retorno[2][1]=$uf;
                $retorno[2][2]=$fecha;
                $retorno[3][1]=$utm;
                $retorno[3][2]=$fecha;
            }
        }
    }
    fclose($arch);
    return $retorno;
}

function verificaArchivoIndicadoresLocal(){
    $anno=date("Y");
    $archivoLocal="./indicadores_" . $anno . ".txt";
    if(!file_exists($archivoLocal)) {
        //crea archivo anual.
        $arch=fopen($archivoLocal, 'a');
        $titulo="FECHA|VALOR UF|VALOR DOLAR|VALOR EURO|VALOR UTM|FECHA-HORA REG\n";
        fwrite($arch, $titulo);
        fclose($arch);
    }
}



function guardaIndicadores($iFecha, $iValorDolar, $iValorEuro, $iValorUF, $iValorUTM){
    $anno=date("Y");
    $fechaHora=date("d-m-y H:i:s");
    $archivoLocal="./indicadores_" . $anno . ".txt";
    if(file_exists($archivoLocal)) {
        //crea archivo anual.
        $arch=fopen($archivoLocal, 'a');
        $registro=$iFecha . "|" . $iValorUF . "|" . $iValorDolar . "|" . $iValorEuro . "|" . $iValorUTM . "|" . $fechaHora . "\n";
        fwrite($arch, $registro);
        fclose($arch);
    }
    
    
}




function traeIndicadores_SBIF(){
    libxml_use_internal_errors(true);
    
    //Fuente Indicadoresdeldia
    //$xmlSource = "http://indicadoresdeldia.cl/webservice/indicadores.xml";    
    
    //Fuente SBIF
    $xmlSourceDolar="https://api.sbif.cl/api-sbifv3/recursos_api/dolar?apikey=3c3637632fa57cf2f1f093dd2c2a5213f86f0a23&formato=xml";
    $xmlSourceEuro="https://api.sbif.cl/api-sbifv3/recursos_api/euro?apikey=3c3637632fa57cf2f1f093dd2c2a5213f86f0a23&formato=xml";
    $xmlSourceUF="https://api.sbif.cl/api-sbifv3/recursos_api/uf?apikey=3c3637632fa57cf2f1f093dd2c2a5213f86f0a23&formato=xml";
    $xmlSourceUTM="https://api.sbif.cl/api-sbifv3/recursos_api/utm?apikey=3c3637632fa57cf2f1f093dd2c2a5213f86f0a23&formato=xml";
    $xmlRespuestaDolar = simplexml_load_file($xmlSourceDolar, 'SimpleXMLElement', LIBXML_NOWARNING);
    if($xmlRespuestaDolar->CodigoHTTP=="404"){
        echo "Indicador no encontrado";
    
    exit;
    }
    

    $xmlRespuestaEuro = simplexml_load_file($xmlSourceEuro);
    $xmlRespuestaUF = simplexml_load_file($xmlSourceUF);
    $xmlRespuestaUTM = simplexml_load_file($xmlSourceUTM);
    
    
    
    if($xmlRespuestaDolar->Dolares->Dolar->Valor==""){
        $xmlRespuesta[0][1]="0";
        //$xmlRespuesta[0][1]="682,79";
        }
    else{
        $xmlRespuesta[0][1]=$xmlRespuestaDolar->Dolares->Dolar->Valor;
        $xmlRespuesta[0][2]=$xmlRespuestaDolar->Dolares->Dolar->Fecha;
    }
    
    if($xmlRespuestaEuro->Euros->Euro->Valor==""){
        $xmlRespuesta[1][1]="0";
        //$xmlRespuesta[1][1]="797,75";
    }
    else{
        $xmlRespuesta[1][1]=$xmlRespuestaEuro->Euros->Euro->Valor;
        $xmlRespuesta[1][2]=$xmlRespuestaEuro->Euros->Euro->Fecha;
    }
    
    if($xmlRespuestaUF->UFs->UF->Valor=="")
        $xmlRespuesta[2][1]="0";
    else{
        $xmlRespuesta[2][1]=$xmlRespuestaUF->UFs->UF->Valor;
        $xmlRespuesta[2][2]=$xmlRespuestaUF->UFs->UF->Fecha;
    }
    
    if ($xmlRespuestaUTM->UTMs->UTM->Valor=="")
        $xmlRespuesta[3][1]="0";
    else{
        $xmlRespuesta[3][1]=$xmlRespuestaUTM->UTMs->UTM->Valor;
        $xmlRespuesta[3][2]=$xmlRespuestaUTM->UTMs->UTM->Fecha;
    }
    
    if($xmlRespuesta[0][1]=="0" || $xmlRespuesta[1][1]=="0" || $xmlRespuesta[2][1]=="0" || $xmlRespuesta[3][1]=="0" ) {
        /**************************************************************** 
        * No enontró todos los indicadores.                             *
        * Busca el ultimo dia completo, con todos los indicadores       *
        ************************************************************** */
        $archivoUltimoCompleto="./ultimosIndicadoresCompletos.txt";    
        $arch2=fopen($archivoUltimoCompleto, 'r+');
        $reg0=fgets($arch2);
        $reg=fgets($arch2);
        list($uFechaDolar, $uValorDolar, $uFechaEuro, $uValorEuro, $uFechaUF, $uValorUF, $uFechaUTM, $uValorUTM, $fechaHora) = explode("|", $reg);
        
        if ($xmlRespuesta[0][1]=="0") {  //Dolar
            $xmlRespuesta[0][1]=$uValorDolar;
            $xmlRespuesta[0][2]=$uFechaDolar;
        }
        
        if($xmlRespuesta[1][1]=="0") {  //Euro
            $xmlRespuesta[1][1]=$uValorEuro;
            $xmlRespuesta[1][2]=$uFechaEuro;

        }
        if($xmlRespuesta[2][1]=="0"){ //UF
            $xmlRespuesta[2][1]=$uValorUF;
            $xmlRespuesta[2][2]=$uFechaUF;
        }
        if($xmlRespuesta[3][1]=="0"){ //UTM   
            $xmlRespuesta[3][1]=$uValorUTM;
            $xmlRespuesta[3][2]=$uFechaUTM;
        }
        fclose($arch2);
    }
    else{
        /**************************************************************** 
        * Enontró todos los indicadores.                                *
        * Guarda los valores en un archivo                              *
        ************************************************************** */   
        $archivoUltimoCompleto="./ultimosIndicadoresCompletos.txt";  
        $arch=fopen($archivoUltimoCompleto, 'w');
        $fechaHora=date("d-m-y H:i:s");
        $fechaHoy=date("d-m-Y");
        $titulo="FECHA DOLAR|VALOR DOLAR|FECHA EURO|VALOR EURO|FECHA UF|VALOR UF|FECHA UTM|VALOR UTM|FECHA-HORA REG\n";
        $registro=$fechaHoy . "|" . $xmlRespuesta[0][1] . "|" . $fechaHoy . "|" . $xmlRespuesta[1][1] . "|" . $fechaHoy . "|" . $xmlRespuesta[2][1] . "|" . $fechaHoy . "|" . $xmlRespuesta[3][1] . "|"  . $fechaHora . "\n";
        fwrite($arch, $titulo);
        fwrite($arch, $registro);
        fclose($arch);
    }        
    return $xmlRespuesta;
}


function traeIndicadores_BCentral(){
    libxml_use_internal_errors(true);
    
    
    ///////////////////////////////////////////////////
    //Fuente Banco Central
    ///////////////////////////////////////////////////
    
    // En construcción
    
    $user='103333237';
    $password='byF853O0';
    $codigoFrecuencia='MONTHLY';
    $wsdl='https://si3.bcentral.cl/sietews/sietews.asmx?wsdl';
    $seriesIds = array ("F072.CLP.EUR.N.O.D");  

    
    /* 
    UF   : F073.UFF.PRE.Z.D
    DOLAR: F073.TCO.PRE.Z.D
    EURO : F072.CLP.EUR.N.O.D
    UTM  : 
    
    */
    //Codigos: F073.TCO.PRE.Z.D , F073.UTR.PRE.Z.M , F073.UFF.PRE.Z.D , F072.CLP.EUR.N.O.D

    $client = new soapclient($wsdl);
    $params = new stdClass;
    
    /*$params->user = $user;
    $params->password = $password;
    $params->frequencyCode = $codigoFrecuencia;
    $result = $client->SearchSeries($params)->SearchSeriesResult;
    foreach ($result->SeriesInfos->internetSeriesInfo as $serie)
    {
     echo $serie->seriesId . " : " . $serie->spanishTitle . "<br/>";
    }
    */
    
    $firstDate = "2018-09-01";
    $lastDate = "2018-09-11";
    $client = new soapclient($wsdl);
    $params = new stdClass;
    $params->user = $user;
    $params->password = $password;
    $params->firstDate = $firstDate;
    $params->lastDate = $lastDate;
    $params->seriesIds = $seriesIds;
    $result = $client->GetSeries($params)->GetSeriesResult;
    $fameSeries =$result->Series->fameSeries;
    //Cuando se solicita una sola serie, la respuesta no es interpretada como un arreglo
    if (is_array($fameSeries ) != 1) $fameSeries = array($fameSeries);
    foreach ($fameSeries as $serieFame)
    {
     echo $serieFame->seriesKey->seriesId . " : " . $serieFame->precision . "<br/>";
     foreach ($serieFame->obs as $obs)
     {
     echo $obs->indexDateString . " : " . $obs->value . "<br/>";
     }
    }
    
    
    
  
    
    //print_r($result);
    
    exit;
    ///////////////////////////////////////////////////
    // FIN Banco Central
    ///////////////////////////////////////////////////
    
    
    
    if($xmlRespuestaDolar->Dolares->Dolar->Valor==""){
        $xmlRespuesta[0][1]="0";
        echo $xmlRespuesta[0][1] . "<br>";
        
        
        //////////////////////////////////////////////
        //Busca el mes completo. EN CONSTRUCCION
        //////////////////////////////////////////////
        $mesBuscado=date("m");
        $annoBuscado=date("Y");
        $xmlSourceDolarMes="https://api.sbif.cl/api-sbifv3/recursos_api/uf/2018/08?apikey=3c3637632fa57cf2f1f093dd2c2a5213f86f0a23&formato=xml";
        $xmlRespuestaDolarMes=simplexml_load_file($xmlSourceDolarMes);   
        
        $ultDol="0";
        $ultFec="0";
        $cont=0;
        foreach ($xmlRespuestaDolarMes->Dolares->Dolar as $dolar){
            $ultFec= $dolar->Fecha;
            $ultDol= $dolar->Valor;
            $cont++;
        }
        echo $ultDol . " " . $ultFec . " " . $cont . "<br>";
        
        
        //exit();
            
    }
    else{
        $xmlRespuesta[0][1]=$xmlRespuestaDolar->Dolares->Dolar->Valor;
        $xmlRespuesta[0][2]=$xmlRespuestaDolar->Dolares->Dolar->Fecha;
    }
    
    if($xmlRespuestaEuro->Euros->Euro->Valor=="")
        $xmlRespuesta[1][1]="0";
    else{
        $xmlRespuesta[1][1]=$xmlRespuestaEuro->Euros->Euro->Valor;
        $xmlRespuesta[1][2]=$xmlRespuestaEuro->Euros->Euro->Fecha;
    }
    
    if($xmlRespuestaUF->UFs->UF->Valor=="")
        $xmlRespuesta[2][1]="0";
    else{
        $xmlRespuesta[2][1]=$xmlRespuestaUF->UFs->UF->Valor;
        $xmlRespuesta[2][2]=$xmlRespuestaUF->UFs->UF->Fecha;
    }
    
    if ($xmlRespuestaUTM->UTMs->UTM->Valor=="")
        $xmlRespuesta[3][1]="0";
    else{
        $xmlRespuesta[3][1]=$xmlRespuestaUTM->UTMs->UTM->Valor;
        $xmlRespuesta[3][2]=$xmlRespuestaUTM->UTMs->UTM->Fecha;
    }
    
    
    
    
    
    
    
    
    return $xmlRespuesta;
}




function muestraMensaje($iMensaje){
    echo $iMensaje . "<br>";
    
}

function cambiaFormato($iVariable){
    $oVariable=str_replace(".", "",$iVariable);
    $oVariable=str_replace(",", ".", $oVariable);
    return $oVariable;
}

function totalVisitasCalculadora(){
    $archivoVisitas="./calculadoraIndicadores.log";
    $totalVisitas=count(file($archivoVisitas));
    return $totalVisitas;
}

?>