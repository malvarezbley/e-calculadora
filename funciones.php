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
    $archivoLog="../calculadoraIndicadores.log";
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

function traeIndicadores(){
    libxml_use_internal_errors(true);
    $xmlSource = "http://indicadoresdeldia.cl/webservice/indicadores.xml";    
    $xmlRespuesta = simplexml_load_file($xmlSource);
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
    $archivoVisitas="../calculadoraIndicadores.log";
    $totalVisitas=count(file($archivoVisitas));
    return $totalVisitas;
}

?>